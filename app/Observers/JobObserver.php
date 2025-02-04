<?php

namespace App\Observers;

use App\Models\Job;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class JobObserver
{
    /**
     * Handle the Job "created" event.
     */
    public function created(Job $job): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Job "updated" event.
     */
    public function updated(Job $job): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Job "deleted" event.
     */
    public function deleted(Job $job): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Job "restored" event.
     */
    public function restored(Job $job): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Job "force deleted" event.
     */
    public function forceDeleted(Job $job): void
    {
        $this->clearCache();
    }

    /**
     * Clear the job cache.
     */
    private function clearCache(): void
    {
        Cache::forget('jobs_paginated');

        if ($userId = optional(Auth::user())->id) {
            Cache::forget("user_{$userId}_jobs");
        }
    }
}
