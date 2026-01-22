<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Check if user can view products
        abort_unless($request->user()->can('products.view'), 403, 'Unauthorized to view products');
        
        $products = Product::with('category')->get();
        return response()->json(['products' => $products]);
    }

    public function store(Request $request)
    {
        // Check permission
        abort_unless($request->user()->can('products.create'), 403, 'Unauthorized to create products');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'pricing' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'images' => 'nullable|string',
        ]);

        $product = Product::create($validated);
        return response()->json(['product' => $product, 'message' => 'Product created successfully'], 201);
    }

    public function show(Request $request, $id)
    {
        // Check permission
        abort_unless($request->user()->can('products.view'), 403, 'Unauthorized to view products');
        
        $product = Product::with('category')->findOrFail($id);
        return response()->json(['product' => $product]);
    }

    public function update(Request $request, $id)
    {
        // Check permission
        abort_unless($request->user()->can('products.update'), 403, 'Unauthorized to update products');
        
        $product = Product::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'pricing' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'images' => 'nullable|string',
        ]);

        $product->update($validated);
        return response()->json(['product' => $product, 'message' => 'Product updated successfully']);
    }

    public function destroy(Request $request, $id)
    {
        // Check permission
        abort_unless($request->user()->can('products.delete'), 403, 'Unauthorized to delete products');
        
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
