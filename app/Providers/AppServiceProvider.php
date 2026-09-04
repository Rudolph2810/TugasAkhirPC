<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use App\Livewire\Notification\NotificationDropdown;
use App\Livewire\Project\ProjectList;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);
        
        // Register Livewire Components
        Livewire::component('notification.notification-dropdown', NotificationDropdown::class);
        Livewire::component('project.project-list', ProjectList::class);
    }
}