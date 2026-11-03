<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::share('btn', 'btn inline-flex items-center justify-center rounded-xl px-5 py-3 font-semibold transition');
    }
}