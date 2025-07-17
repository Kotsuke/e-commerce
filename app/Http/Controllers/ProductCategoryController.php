<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Categories;
class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Categories::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('description', 'like', '%' . $request->q . '%');
            })
            ->paginate(10);

        return view('dashboard.categories.index', [
            'categories' => $categories,
            'q' => $request->q
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'description' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with(
                [
                    'errors' => $validator->errors(),
                    'errorMessage' => 'Validasi Error, Silahkan lengkapi data terlebih dahulu'
                ]
            );
        }

        $category = new Categories;
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->description = $request->description;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('uploads/categories', $imageName, 'public');
            $category->image = $imagePath;
        }

        $category->save();

        return redirect()->back()
            ->with(
                [
                    'successMessage' => 'Data Berhasil Disimpan'
                ]
            );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Categories::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $category = Categories::find($id);

        return view('dashboard.categories.edit',[
            'category'=>$category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'description' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with(
                [
                    'errors'=>$validator->errors(),
                    'errorMessage'=>'Validasi Error, Silahkan lengkapi data terlebih dahulu'
                ]
            );
        }

        $category = Categories::find($id);
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->description = $request->description;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('uploads/categories', $imageName, 'public');
            $category->image = $imagePath;
        }

        $category->save();

        return redirect()->back()
            ->with(
                [
                    'successMessage'=>'Data Berhasil Disimpan'
                ]
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Categories::find($id);

        $category->delete();

        return redirect()->back()
            ->with(
                [
                    'successMessage'=>'Data Berhasil Dihapus'
                ]
            );

    }

    public function sync($id, Request $request)
    {
        $category = Categories::findOrFail($id);

        // Determine the intended active state based on the request from the UI
        // If $request->is_active is 1 (meaning the UI sent "ON"), then $intended_active_state should be true.
        // If $request->is_active is 0 (meaning the UI sent "OFF"), then $intended_active_state should be false.
        $intended_active_state = (bool) $request->is_active;

        $response = Http::post('https://api.phb-umkm.my.id/api/product-category/sync', [
            'client_id' => env('CLIENT_ID'),
            'client_secret' => env('CLIENT_SECRET'),
            'seller_product_category_id' => (string) $category->id,
            'name' => $category->name,
            'description' => $category->description,
            'is_active' => $intended_active_state, // CORRECTED: Pass the actual intended state
        ]);

        // Always log the response to aid debugging, especially for non-200 responses
        Log::info('API Category Sync Attempt for ID: ' . $id, [
            'request_payload' => [
                'client_id' => env('CLIENT_ID'), // Only include if necessary for debug, be careful with sensitive info
                // 'client_secret' => '*****', // Don't log actual secret
                'seller_product_category_id' => (string) $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'is_active' => $intended_active_state,
            ],
            'response_status' => $response->status(),
            'response_body' => $response->body(),
        ]);

        if ($response->successful() && isset($response['product_category_id'])) {
            // If the external API says it's successful AND returns an ID, update locally.
            // If the intended state is active, save the ID. If inactive, set to null.
            if ($intended_active_state) {
                $category->hub_category_id = $response['product_category_id'];
            } else {
                $category->hub_category_id = null;
            }
            // Also update the local 'is_active' column if your 'categories' table has one
            // Assuming you have an 'is_active' column in your 'categories' table to reflect the toggle state
            $category->is_active = $intended_active_state;
            $category->save();

            session()->flash('successMessage', 'Category Synced Successfully');
        } else {
            // Handle API errors
            $errorMessage = 'Failed to sync category with external API.';
            $responseBody = $response->body();
            if (!empty($responseBody)) {
                // Attempt to parse JSON error if available
                $jsonResponse = json_decode($responseBody, true);
                if (json_last_error() === JSON_ERROR_NONE && isset($jsonResponse['message'])) {
                    $errorMessage .= ' Error: ' . $jsonResponse['message'];
                } else {
                    $errorMessage .= ' Response: ' . $responseBody; // Fallback to raw response
                }
            }

            Log::error('API Category Sync Failed for ID: ' . $id, [
                'request_payload' => [
                    'seller_product_category_id' => (string) $category->id,
                    'is_active' => $intended_active_state,
                ],
                'response_status' => $response->status(),
                'response_body' => $responseBody,
                'error_message_for_user' => $errorMessage,
            ]);

            session()->flash('errorMessage', $errorMessage);
        }

        return redirect()->back();
    }
}
