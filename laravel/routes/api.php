<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\Product;
use App\Models\Category;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;


Route::post('/login', function (Request $request) {
    $request->validate(['email' => 'required|email', 'password' => 'required']);

    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $user = Auth::user();
    $token = $user->createToken('mobile')->plainTextToken;

    return response()->json(['token' => $token]);
});


Route::middleware(['auth:sanctum'])->group(function () {

    // Current authenticated user info
    Route::get('/user', fn(Request $request) => $request->user());
    Route::get('/me', fn(Request $request) => $request->user()->load('roles'));

    Route::controller(CategoryController::class)->prefix('categories')->group(function() {
        Route::get('/', 'getCategories');                  // GET /api/categories
        Route::post('/', 'createCategory');                // POST /api/categories
        Route::get('/{categoryId}', 'getCategory');        // GET /api/categories/{categoryId}
        Route::patch('/{categoryId}', 'updateCategory');  // PATCH /api/categories/{categoryId}
        Route::delete('/{categoryId}', 'deleteCategory'); // DELETE /api/categories/{categoryId}
    });

    Route::patch('/categories/{category}/status', function (Request $request, Category $category) {
        abort_unless($request->user()->can('updateStatus', $category), 403);

        $validated = $request->validate(['status' => 'required|string']);
        $category->update(['status' => $validated['status']]);

        return response()->json(['category' => $category]);
    });


    Route::controller(ProductController::class)->prefix('products')->group(function() {
        Route::get('/', 'index');          // GET /api/products
        Route::post('/', 'store');         // POST /api/products
        Route::get('/{id}', 'show');       // GET /api/products/{id}
        Route::patch('/{id}', 'update');   // PATCH /api/products/{id}
        Route::delete('/{id}', 'destroy'); // DELETE /api/products/{id}
    });

    Route::post('/products', function (Request $request) {
        abort_unless($request->user()->can('products.create'), 403);

        $validated = $request->validate([
            'name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'pricing' => 'required|numeric',
        ]);

        $product = Product::create($validated);
        return response()->json(['product' => $product], 201);
    });


    Route::get('/tasks', function (Request $request) {
        $user = $request->user();

        if ($user->hasRole('admin')) {
            $tasks = Task::with(['products', 'assignedUser'])->get();
        } elseif ($user->hasRole('manager')) {
            $tasks = Task::whereHas('products', fn($q) =>
            $q->where('created_by', $user->id)
            )->with(['products', 'assignedUser'])->get();
        } else {
            $tasks = Task::where('assigned_to', $user->id)
                ->with(['products', 'assignedUser'])->get();
        }

        return response()->json(['tasks' => $tasks]);
    });


    Route::patch('/tasks/{task}/status', function (Request $request, Task $task) {
        abort_unless($request->user()->can('updateStatus', $task), 403);

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed'
        ]);

        $task->update(['status' => $validated['status']]);
        return response()->json(['task' => $task]);
    });

});

