<?php

namespace App\Providers;

use App\Models\Application;
use App\Models\Job;
use App\Observers\ApplicationObserver;
use App\Observers\JobObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Job::observe(JobObserver::class);
        Application::observe(ApplicationObserver::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
