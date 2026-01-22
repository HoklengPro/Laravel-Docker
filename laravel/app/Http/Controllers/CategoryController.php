<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // --- Get /api/categories
    public function getCategories(Request $request) 
    {
        $user = $request->user();
        
        // Check if user can view categories
        abort_unless($user->can('categories.view'), 403, 'Unauthorized to view categories');
        
        $categories = Category::all();
        return response()->json(['categories' => $categories]);
    }

    // --- Post /api/categories
    public function createCategory(Request $request) 
    {
        // Check permission
        abort_unless($request->user()->can('categories.create'), 403, 'Unauthorized to create categories');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = Category::create($validated);
        return response()->json(['category' => $category, 'message' => 'Category created successfully'], 201);
    }

    // --- Get /api/categories/{categoryId}
    public function getCategory(Request $request, $categoryId) 
    {
        $user = $request->user();
        abort_unless($user->can('categories.view'), 403, 'Unauthorized to view categories');
        
        $category = Category::findOrFail($categoryId);
        return response()->json(['category' => $category]);
    }

    // --- Patch /api/categories/{categoryId}
    public function updateCategory(Request $request, $categoryId) 
    {
        abort_unless($request->user()->can('categories.update'), 403, 'Unauthorized to update categories');
        
        $category = Category::findOrFail($categoryId);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);
        return response()->json(['category' => $category, 'message' => 'Category updated successfully']);
    }

    // --- Delete /api/categories/{categoryId}
    public function deleteCategory(Request $request, $categoryId) 
    {
        abort_unless($request->user()->can('categories.delete'), 403, 'Unauthorized to delete categories');
        
        $category = Category::findOrFail($categoryId);
        $category->delete();
        
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
