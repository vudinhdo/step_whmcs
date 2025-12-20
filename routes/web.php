<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductConfiguratorController;

use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\TicketController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\InvoiceController;
use App\Http\Controllers\Client\ServiceController;
use App\Http\Controllers\Client\PaymentController;

use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| 1) GUEST / PUBLIC (khách vãng lai - không cần đăng nhập)
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'home'])->name('landing');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categories/{group:slug}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Cloud configurator estimate (guest dùng được)
Route::post('/products/{product:slug}/estimate', [ProductConfiguratorController::class, 'estimate'])
    ->name('products.estimate');

// Cart (guest dùng được)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Checkout (thường bắt login, nhưng bạn đang để public show/thankyou theo token)
// Tuỳ bạn: nếu muốn bắt login thì thêm middleware('auth') cho show/place
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.place');
Route::get('/order/{token}', [CheckoutController::class, 'thankYou'])->name('checkout.thankyou');


/*
|--------------------------------------------------------------------------
| 2) CLIENT PORTAL (đã đăng ký / đăng nhập)
|--------------------------------------------------------------------------
| - prefix: /client/...
| - route name: client.*
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'force_password'])
    ->prefix('client')
    ->name('client.')
    ->group(function () {

        // Dashboard portal (đổi từ /dashboard sang /client/dashboard cho rõ)
        // Nếu bạn muốn giữ /dashboard thì xem note bên dưới.
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Tickets
        Route::get('/tickets',              [TicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/create',       [TicketController::class, 'create'])->name('tickets.create');
        Route::post('/tickets',             [TicketController::class, 'store'])->name('tickets.store');
        Route::get('/tickets/{ticket}',     [TicketController::class, 'show'])->name('tickets.show');
        Route::post('/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');

        // Orders (portal order)
        Route::get('/orders', [OrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/{order}', [OrderController::class, 'show'])
            ->name('orders.show');

        Route::post('/products/{product}/order', [OrderController::class, 'store'])
            ->name('orders.store');



        // Invoices
        Route::get('/invoices',             [InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/{invoice}',   [InvoiceController::class, 'show'])->name('invoices.show');

        // Services
        Route::get('/services',             [ServiceController::class, 'index'])->name('services.index');
        Route::get('/services/{service}',   [ServiceController::class, 'show'])->name('services.show');

        // Test payment (tạm thời)
        Route::post('/invoices/{invoice}/pay/test', [PaymentController::class, 'payTest'])
            ->name('invoices.pay.test');
    });


/*
|--------------------------------------------------------------------------
| 2.1) CLIENT DASHBOARD alias (giữ URL /dashboard như Breeze)
|--------------------------------------------------------------------------
| Nếu bạn muốn /dashboard vẫn hoạt động như cũ:
| - Route này chỉ redirect sang /client/dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return redirect()->route('client.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| 3) PROFILE (auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| 4) ADMIN (Filament)
|--------------------------------------------------------------------------
| Filament tự đăng ký route /admin.
| Bạn KHÔNG cần khai báo ở web.php (trừ khi muốn thêm trang admin custom).
|--------------------------------------------------------------------------
*/


require __DIR__.'/auth.php';
