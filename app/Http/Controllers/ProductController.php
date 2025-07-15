<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Categories;

// CANT UNDERSTAND SHIT

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->q . '%')
                      ->orWhere('description', 'like', '%' . $request->q . '%');
            })
            ->paginate(10);

        return view('dashboard.products.index', [
            'products' => $products,
            'q' => $request->q
        ]);
    }

    public function create()
    {
        $categories = Categories::all();
        return view('dashboard.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'sku' => 'required|string|max:50',
            'stock' => 'required|integer',
            'is_active' => 'required|boolean',
            'product_category_id' => 'required|exists:product_categories,id',
            'description' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with([
                'errors' => $validator->errors(),
                'errorMessage' => 'Validasi Error, Silahkan lengkapi data terlebih dahulu'
            ]);
        }

        $product = new Product;
        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->sku = $request->sku;
        $product->stock = $request->stock;
        $product->is_active = $request->is_active;
        $product->description = $request->description;
        $product->product_category_id = $request->product_category_id;
        $product->price = $request->price;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('uploads/products', $imageName, 'public');
            $product->image_url = $imagePath;
        }

        $product->save();

        return redirect()->back()->with(['successMessage' => 'Produk berhasil disimpan']);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Categories::all();

        return view('dashboard.products.edit', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

    public function update(Request $request, string $id)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'sku' => 'required|string|max:50',
            'stock' => 'required|integer',
            'is_active' => 'required|boolean',
            'product_category_id' => 'required|exists:product_categories,id',
            'description' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with([
                'errors' => $validator->errors(),
                'errorMessage' => 'Validasi Error, Silahkan lengkapi data terlebih dahulu'
            ]);
        }

        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->sku = $request->sku;
        $product->stock = $request->stock;
        $product->is_active = $request->is_active;
        $product->description = $request->description;
        $product->product_category_id = $request->product_category_id;
        $product->price = $request->price;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('uploads/products', $imageName, 'public');
            $product->image_url = $imagePath;
        }

        $product->save();

        return redirect()->back()->with(['successMessage' => 'Produk berhasil diupdate']);
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->back()->with(['successMessage' => 'Produk berhasil dihapus']);
    }

    public function sync($id, Request $request)
      {
          $product = Product::findOrFail($id);
  
          $response = Http::post('https://api.phb-umkm.my.id/api/product/sync', [
              'client_id' => env('CLIENT_ID'),
              'client_secret' => env('CLIENT_SECRET'),
              'seller_product_id' => (string) $product->id,
              'name' => $product->name,
              'description' => $product->description,
              'price' => $product->price,
              'stock' => $product->stock,
              'sku' => $product->sku,
              'image_url' => $product->image_url,
              'weight' => $product->weight,
              'is_active' => $request->is_active == 1 ? false : true,
              'category_id' => (string) $product->category->hub_category_id,
          ]);
  
          if ($response->successful() && isset($response['product_id'])) {
              $product->hub_product_id = $request->is_active == 1 ? null : $response['product_id'];
              $product->save();
          }
  
          session()->flash('successMessage', 'Product Synced Successfully');
          return redirect()->back();
      }
}
