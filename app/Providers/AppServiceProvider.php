<?php

namespace App\Providers;

use App\Models\Pelanggaran;
use App\Observers\WAObserver;
use App\Observers\WA1Observer;
use App\Observers\WA2Observer;
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
        Pelanggaran::observe(WAObserver::class);
        
    }
}
