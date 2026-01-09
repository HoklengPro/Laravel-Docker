<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Admin bypass - admins can do everything
        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });

        // User management
        Gate::define('users.manage', fn($user) => $user->hasPermission('users.manage'));

        // Product gates
        Gate::define('products.create', fn($user) => $user->hasPermission('products.create'));
        Gate::define('products.update', fn($user) => $user->hasPermission('products.update'));
        Gate::define('products.delete', fn($user) => $user->hasPermission('products.delete'));
        Gate::define('products.view', fn($user) => $user->hasPermission('products.view'));

        // Category gates
        Gate::define('categories.create', fn($user) => $user->hasPermission('categories.create'));
        Gate::define('categories.update', fn($user) => $user->hasPermission('categories.update'));
        Gate::define('categories.delete', fn($user) => $user->hasPermission('categories.delete'));
        Gate::define('categories.view', fn($user) => $user->hasPermission('categories.view'));
    }
}
