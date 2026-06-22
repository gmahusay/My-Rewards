<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = auth()->user()->products()->paginate(10);
        return view('business.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        return view('business.products.create');
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price_cash' => ['required', 'numeric', 'min:0'],
            'price_points' => ['required', 'integer', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = $request->except('image');
        $data['business_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('business.products.index')->with('status', 'Product created successfully!');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        // Ensure the product belongs to the authenticated business
        if ($product->business_id !== auth()->id()) {
            abort(403);
        }

        return view('business.products.edit', compact('product'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        if ($product->business_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price_cash' => ['required', 'numeric', 'min:0'],
            'price_points' => ['required', 'integer', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('business.products.index')->with('status', 'Product updated successfully!');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->business_id !== auth()->id()) {
            abort(403);
        }

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('business.products.index')->with('status', 'Product deleted successfully!');
    }
}
