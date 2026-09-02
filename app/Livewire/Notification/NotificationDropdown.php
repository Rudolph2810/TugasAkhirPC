<?php

namespace App\Livewire\Notification;

use Livewire\Component;
use Illuminate\Notifications\DatabaseNotification;

class NotificationDropdown extends Component
{
    public $notifications = [];
    public $unreadCount = 0;

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $user = auth()->user();
        if ($user) {
            $this->unreadCount = $user->unreadNotifications()->count();
            $this->notifications = $user->notifications()
                ->take(10)
                ->get()
                ->map(function ($notification) {
                    $data = $notification->data;
                    $data['id'] = $notification->id;
                    $data['read_at'] = $notification->read_at;
                    $data['created_at'] = $notification->created_at->diffForHumans();
                    return $data;
                })
                ->toArray();
        }
    }

    public function markAsRead($notificationId)
    {
        $notification = DatabaseNotification::find($notificationId);
        if ($notification && $notification->notifiable_id === auth()->id()) {
            $notification->markAsRead();
        }
        $this->loadNotifications();
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.notification.notification-dropdown');
    }
}