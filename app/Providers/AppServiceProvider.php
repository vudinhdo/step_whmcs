<?php

namespace App\Providers;

use App\Models\Order;
use App\Observers\OrderObserver;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Filament::serving(function () {
            Filament::registerNavigationGroups([
                'Sản phẩm',
                'Khách hàng & Đơn hàng',
                'Thanh toán',
            ]);
        });
        Order::observe(OrderObserver::class);
//
//        Filament::auth(function () {
//            $user = Auth::user();
//            return $user && in_array($user->role, ['admin', 'staff','client']);
//        });
    }
}
