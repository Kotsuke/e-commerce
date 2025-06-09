<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::orderBy('menu_order')->get();
        return view('menus.index', compact('menus'));
    }

    public function create()
    {
        return view('menus.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'menu_text' => 'required|string|max:255',
            'menu_icon' => 'nullable|string|max:255',
            'menu_url' => 'nullable|string|max:255',
            'menu_order' => 'integer',
            'status' => 'boolean',
        ]);

        Menu::create($request->all());

        return redirect()->route('menus.index')->with('successMessage', 'Menu created successfully.');
    }

    public function show(Menu $menu)
    {
        return view('menus.show', compact('menu'));
    }

    public function edit(Menu $menu)
    {
        return view('menus.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'menu_text' => 'required|string|max:255',
            'menu_icon' => 'nullable|string|max:255',
            'menu_url' => 'nullable|string|max:255',
            'menu_order' => 'integer',
            'status' => 'boolean',
        ]);

        $menu->update($request->all());

        return redirect()->route('menus.index')->with('successMessage', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('menus.index')->with('successMessage', 'Menu deleted successfully.');
    }
}
