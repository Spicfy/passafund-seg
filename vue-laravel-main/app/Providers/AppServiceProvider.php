<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Models\User;

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
        {
            
            ResetPassword::createUrlUsing(function (User $user, string $token) {
                $frontendUrl = env('FRONTEND_URL', 'http://localhost:8081');
                return $frontendUrl . '/reset-password?token=' . $token . '&email=' . $user->email;
            });
        }
    }
}
