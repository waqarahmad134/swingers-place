<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

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
        // Share route prefix with all admin views for role-based routing
        View::composer('layouts.admin', function ($view) {
            $isAdmin = Auth::check() && (Auth::user()->is_admin ?? false);
            $routePrefix = $isAdmin ? 'admin' : 'editor';
            $view->with('routePrefix', $routePrefix);
        });

        // Also share with all admin.* views
        View::composer('admin.*', function ($view) {
            $isAdmin = Auth::check() && (Auth::user()->is_admin ?? false);
            $routePrefix = $isAdmin ? 'admin' : 'editor';
            $view->with('routePrefix', $routePrefix);
        });
    }
}
