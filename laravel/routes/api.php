<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// If your controller has getCategories(), createCategory(), etc.
Route::controller(CategoryController::class)->prefix('categories')->group(function() {
    Route::get('/', 'getCategories');             // GET /api/categories
    Route::post('/', 'createCategory');           // POST /api/categories
    Route::get('/{categoryId}', 'getCategory');   // GET /api/categories/{categoryId}
    Route::patch('/{categoryId}', 'updateCategory'); // PATCH /api/categories/{categoryId}
    Route::delete('/{categoryId}', 'deleteCategory'); // DELETE /api/categories/{categoryId}
});

// For products - adjust based on your ProductController methods
Route::controller(ProductController::class)->prefix('products')->group(function() {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}', 'show');
    Route::patch('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});
