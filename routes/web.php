<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RedeemController;

/* -- Admin Panel Section -- */ 

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TokensController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrdersController;
use App\Models\Order;
use App\Models\Product;
use App\Models\Token;
use App\Models\TokenLog;
use Inertia\Inertia;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Inertia::render('Home');
});

Route::get('/dashboard', function () {
    $userId = auth()->id();

    $currentBalance = Token::where('user_id', $userId)->value('balance') ?? 0;

    $earnedThisMonth = TokenLog::where('user_id', $userId)
        ->whereYear('created_at', now()->year)
        ->whereMonth('created_at', now()->month)
        ->sum('amount');

    $redeemedThisMonth = Order::where('user_id', $userId)
        ->whereYear('created_at', now()->year)
        ->whereMonth('created_at', now()->month)
        ->sum('tokens_spent');

    $pendingRequests = Order::where('user_id', $userId)
        ->whereIn('status', ['Pending', 'Processing', 'Shipped'])
        ->count();

    return Inertia::render('Dashboard', [
        'metrics' => [
            'currentBalance' => (int) $currentBalance,
            'earnedThisMonth' => (int) $earnedThisMonth,
            'redeemedThisMonth' => (int) $redeemedThisMonth,
            'pendingRequests' => (int) $pendingRequests,
        ],
        'products' => Product::query()
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->latest()
            ->get([
                'id',
                'name',
                'description',
                'image_url',
                'token_cost',
                'stock',
            ]),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/products/{product}/redeem', [RedeemController::class, 'store'])
        ->middleware('verified')
        ->name('products.redeem');
});



/* --- Admin Routes ---- */

Route::prefix('admin')->group(function () {

    // Admin Login Page
    Route::get('/login', [AdminAuthController::class, 'showLogin'])
        ->name('admin.login');

    // Admin Login Submit
    Route::post('/login', [AdminAuthController::class, 'login'])
        ->name('admin.login.submit');

});

Route::prefix('admin')
    ->middleware(['auth:admin'])
    ->as('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('products', ProductController::class);

        Route::get('/orders', [OrdersController::class, 'index'])
            ->name('orders');

        Route::get('/orders/{order}', [OrdersController::class, 'show'])
            ->name('orders.show');

        Route::post('/orders/{order}/status', [OrdersController::class, 'updateStatus'])
            ->name('orders.status');

        Route::post('/orders/{order}/tracking', [OrdersController::class, 'updateTracking'])
            ->name('orders.tracking');

        Route::post('/orders/{order}/cancel', [OrdersController::class, 'cancel'])
            ->name('orders.cancel');

        Route::get('/tokens', [TokensController::class, 'index'])
            ->name('tokens');

        Route::post('/tokens/grant', [TokensController::class, 'grant'])
            ->name('tokens.grant');

        Route::get('/tokens/export', [TokensController::class, 'export'])
            ->name('tokens.export');

        Route::get('/users', [UserController::class, 'index'])
            ->name('users');

        Route::post('/users/{user}/role', [UserController::class, 'updateRole'])
            ->name('users.role');

        Route::post('/logout', [AdminAuthController::class, 'logout'])
            ->name('logout');
    });

require __DIR__.'/auth.php';
