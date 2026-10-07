<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', function (Illuminate\Http\Request $request) {
    $items = \App\Models\Item::where('status', 'active')
        ->when($request->search, function ($query) use ($request) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
        })
        ->paginate(25)
        ->withQueryString();
    return view('dashboard', compact('items'));
})->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{itemId}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/{itemId}/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // Staff/Admin order management
    Route::middleware('staff')->group(function () {
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });
});
require __DIR__ . '/auth.php';
Route::middleware('auth')->group(function () {
    Route::get('/items', [ItemController::class, 'index']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('admin')->group(function () {
        Route::get('/items/create', [ItemController::class, 'create']);
        Route::post('/items', [ItemController::class, 'store']);
        Route::get('/items/{id}', [ItemController::class, 'show']);
        Route::get('/items/{id}/edit', [ItemController::class, 'edit']);
        Route::post('/items/update/{id}', [ItemController::class, 'update']);
        Route::post('/items/{id}/delete', [ItemController::class, 'destroy']);

        // User management
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::patch('/admin/users/{user}/role', [UserController::class, 'updateRole'])->name('admin.users.updateRole');
        Route::patch('/admin/users/{user}/status', [UserController::class, 'toggleStatus'])->name('admin.users.toggleStatus');
    });
});

