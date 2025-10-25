<?php

use App\Models\User;
use Livewire\Volt\Component;

new class extends Component {
    public User $user;

    public function viewNotification()
    {
        $authUser = Auth::user();
        $authUser->userNotifications()->where('is_viewed', false)->update(['is_viewed' => true]);


    }
};
?>


<div wire:poll.3s
    x-data="{ notifications : false }"
>
    <button
        @click="notifications=true"
        wire:click="viewNotification"
    >
        <i class="fas fa-bell text-lg"></i> 
        <span class="badge">
            {{ Auth::user()->userNotifications()->where('is_viewed', false)->count() }}
        </span>
    </button>

    <div 
        x-show="notifications"
        @click="open = false"
        x-transition.opacity
        class="fixed inset-0 z-40 bg-transparent cursor-default"
    ></div>

    <div
    x-show="notifications"
    @click.away="notifications=false"
    class="fixed inset-0 top-12 bottom-15 flex justify-center p-4 white dark:bg-zinc-800 z-40 overflow-auto">
        <div class="flex flex-col w-full">
            <h3 class="text-2xl font-semibold">Notifications</h3>
            <div class="flex flex-col space-y-4 py-4">
                @forelse (Auth::user()->userNotifications->sortByDesc('created_at') as $notification)
                    <div class="p-4 rounded-lg bg-gray-100 dark:bg-zinc-700/50">
                        <h4 class="font-bold">{{ $notification->title }}</h4>
                        <p>{{ $notification->message }}</p>
                        <span class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <div class="m-auto">
                        <span>No notifications to show</span>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

