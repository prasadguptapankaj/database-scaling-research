<?php

namespace App\Providers;

use App\Auth\ShardedUserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Hashing\Hasher;
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
        Auth::provider('sharded', function ($app, array $config) {
            // Ensure to pass the hasher and the model
            return new ShardedUserProvider($app['hash'], $config['model']);
        });
    }
}
