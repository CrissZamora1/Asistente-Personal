<?php

namespace App\Providers;

use App\Models\CommandLog;
use App\Observers\CommandLogObserver;
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
        CommandLog::observe(CommandLogObserver::class);
    }
}
