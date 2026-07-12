<?php

namespace App\Providers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // 1. Import class Paginator

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Paginator::useBootstrapFive(); // 2. Set default pagination ke Bootstrap 5

        View::composer(['layouts.app', 'home'], function ($view) {
            $cartCount = $view->getData()['cartCount'] ?? 0;
            $orderPaymentActionCount = 0;

            if (Auth::check()) {
                $cartKey = 'cart_user_' . Auth::id();
                $cartCount = count(session($cartKey, []));
                $orderPaymentActionCount = Order::countCustomerPaymentActions(Auth::id());
            } elseif (! $view->offsetExists('cartCount')) {
                $cartCount = count(session('cart_guest', []));
            }

            $view->with([
                'cartCount' => $cartCount,
                'orderPaymentActionCount' => $orderPaymentActionCount,
            ]);
        });
    }
}
