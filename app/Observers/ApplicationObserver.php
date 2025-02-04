<?php

namespace App\Observers;

use App\Models\Application;
use Illuminate\Support\Facades\Cache;

class ApplicationObserver
{
    /**
     * Handle the Application "created" event.
     */
    public function created(Application $application): void
    {
        $this->clearCache($application);
    }

    /**
     * Handle the Application "updated" event.
     */
    public function updated(Application $application): void
    {
        $this->clearCache($application);
    }

    /**
     * Handle the Application "deleted" event.
     */
    public function deleted(Application $application): void
    {
        $this->clearCache($application);
    }

    /**
     * Handle the Application "restored" event.
     */
    public function restored(Application $application): void
    {
        $this->clearCache($application);
    }

    /**
     * Handle the Application "force deleted" event.
     */
    public function forceDeleted(Application $application): void
    {
        $this->clearCache($application);
    }

    /**
     * Clear the job cache.
     */
    private function clearCache(Application $application): void
    {
        Cache::forget("job_{$application->job_id}_applications");
    }
}
