<?php

namespace App\Providers;

use Illuminate\Http\Response;
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
        Response::macro('success', function() {
            return response()->json([
                'success' => true
            ]);
        });

        Response::macro('error', function($data) {
            return response()->json([
                'data' => $data
            ], 422);
        });
    }
}
