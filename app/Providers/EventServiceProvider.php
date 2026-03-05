<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Events\TaskAssigned;
use App\Listeners\CreateTaskNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        TaskAssigned::class => [
            CreateTaskNotification::class,
        ],
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
