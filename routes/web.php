<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{ShopController,CartController,OrderController,AuthController,AdminController,StaffController,OwnerController,SearchController,WishlistController,PasswordResetController};

Route::get('/', [ShopController::class,'home'])->name('home');
Route::get('/shop/{category}', [ShopController::class,'category'])->name('category');
Route::get('/product/{product}', [ShopController::class,'product'])->name('product');
Route::get('/cart', [CartController::class,'show'])->name('cart');
Route::post('/cart/add', [CartController::class,'add'])->name('cart.add');
Route::post('/cart/remove/{variant}', [CartController::class,'remove'])->name('cart.remove');
Route::get('/login', [AuthController::class,'loginForm'])->name('login');
Route::post('/login', [AuthController::class,'login']);
Route::get('/register', [AuthController::class,'registerForm'])->name('register');
Route::post('/register', [AuthController::class,'register']);
Route::post('/logout', [AuthController::class,'logout'])->name('logout');

Route::get('/search', [SearchController::class,'index'])->name('search');

Route::get('/forgot-password', [PasswordResetController::class,'request'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class,'email'])->middleware('throttle:5,1')->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class,'form'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class,'update'])->name('password.update');

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [OrderController::class,'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class,'store'])->name('order.store');
    Route::get('/orders', [OrderController::class,'index'])->name('orders');
    Route::get('/orders/{order}', [OrderController::class,'show'])->name('order.show');
    Route::get('/wishlist', [WishlistController::class,'index'])->name('wishlist');
    Route::post('/wishlist/{product}', [WishlistController::class,'toggle'])->name('wishlist.toggle');
});

// Stock Checker + order handling: Staff and Admin only
Route::prefix('admin')->middleware(['auth', \App\Http\Middleware\StaffOnly::class])->group(function () {
    Route::get('/', [AdminController::class,'stock'])->name('admin');
    Route::post('/stock/{variant}', [AdminController::class,'adjust'])->name('admin.adjust');
    Route::get('/orders', [AdminController::class,'orders'])->name('admin.orders');
    Route::post('/orders/{order}', [AdminController::class,'status'])->name('admin.status');
});

// Staff dashboard + product management: Staff and Admin
Route::prefix('staff')->middleware(['auth', \App\Http\Middleware\StaffOnly::class])->group(function () {
    Route::get('/', [StaffController::class, 'dashboard'])->name('staff');
    Route::get('/products', [StaffController::class, 'products'])->name('staff.products');
    Route::get('/products/create', [StaffController::class, 'create'])->name('staff.products.create');
    Route::post('/products', [StaffController::class, 'store'])->name('staff.products.store');
    Route::get('/products/{product}/edit', [StaffController::class, 'edit'])->name('staff.products.edit');
    Route::put('/products/{product}', [StaffController::class, 'update'])->name('staff.products.update');
    Route::delete('/products/{product}', [StaffController::class, 'destroy'])->name('staff.products.destroy');
});

// Administrator only: dashboard, sales reports, low-stock thresholds, stock history
Route::prefix('admin')->middleware(['auth', \App\Http\Middleware\AdminOnly::class])->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('owner.dashboard');
    Route::get('/reports', [OwnerController::class, 'reports'])->name('owner.reports');
    Route::get('/thresholds', [OwnerController::class, 'thresholds'])->name('owner.thresholds');
    Route::post('/thresholds', [OwnerController::class, 'setAllThresholds'])->name('owner.thresholds.all');
    Route::post('/thresholds/{variant}', [OwnerController::class, 'setThreshold'])->name('owner.thresholds.set');
    Route::get('/history', [OwnerController::class, 'history'])->name('owner.history');
});
