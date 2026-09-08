<?php

namespace App\Providers;

use App\Http\Controllers\Admin\AdminRegisterController;
use Backpack\CRUD\app\Http\Controllers\Auth\RegisterController as BackpackRegisterController;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Keep Backpack's routes intact while using the application-specific
        // registration behavior for the /admin/register controller.
        $this->app->bind(BackpackRegisterController::class, AdminRegisterController::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
