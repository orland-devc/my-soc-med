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

    public function clickNotification($notificationId)
    {
        $authUser = Auth::user();
        $notification = $authUser->userNotifications()->where('id', $notificationId)->first();

        if ($notification && !$notification->is_read) {
            $notification->is_read = true;
            $notification->save();
        }

        $this->redirectIntended($notification->link, navigate: true);
    }
};
?>

<div wire:poll.3s x-data="{ notifications: false }">
    <!-- Notification Bell Button -->
    <button
        @click="notifications = true"
        wire:click="viewNotification"
        class="relative p-2 rounded-full flex items-center hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors duration-200"
    >
        <i class="fas fa-bell text-xl text-gray-900 dark:text-gray-300"></i>
        
        @if(Auth::user()->userNotifications()->where('is_viewed', false)->count() > 0)
            <span class="absolute top-0 right-0 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">
                {{ Auth::user()->userNotifications()->where('is_viewed', false)->count() }}
            </span>
        @endif
    </button>

    <!-- Backdrop -->
    <div 
        x-show="notifications"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="notifications = false"
        class="fixed inset-0 z-40 bg-black/20 dark:bg-black/40 backdrop-blur-sm"
        style="display: none;"
    ></div>

    <!-- Notification Panel -->
    <div
        x-show="notifications"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        @click.away="notifications = false"
        class="fixed left-4 right-4 bottom-16 top-16 z-50"
        style="display: none;"
    >
        <div class="rounded-2xl bg-white dark:bg-zinc-900 shadow-2xl ring-1 ring-black/5 dark:ring-white/10 h-full overflow-hidden">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-zinc-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Notifications</h3>
                <button 
                    @click="notifications = false"
                    class="p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors"
                >
                    <i class="fas fa-times text-gray-500 dark:text-gray-400"></i>
                </button>
            </div>

            <!-- Notifications List -->
            <div class="overflow-y-auto">
                @forelse (Auth::user()->userNotifications->sortByDesc('created_at') as $notification)
                    <div wire:click="clickNotification({{$notification->id}})" class="cursor-pointer px-6 py-4 transition-colors border-b border-gray-100 dark:border-zinc-800 last:border-b-0 relative {{ $notification->is_read ? '' : 'bg-blue-800/10 hover:bg-blue-800/20' }}"
>
                        <!-- Unread indicator -->
                        @if(!$notification->is_read)
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500"></div>
                        @endif
                        <div class="flex items-start gap-3">
                            <!-- Icon -->
                            <div class="flex-shrink-0 mt-1">
                                    <img 
                                        src="{{ $notification->fromUser->profile_photo_path }}" 
                                        alt="{{ $notification->fromUser->name }}" 
                                        class="w-10 h-10 rounded-full object-cover"
                                    >
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">
                                    {{ $notification->title }}
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                                    {{ $notification->message }}
                                </p>
                                <span class="text-xs text-gray-500 dark:text-gray-500 mt-2 inline-block">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-zinc-800 flex items-center justify-center">
                            <i class="fas fa-bell-slash text-2xl text-gray-400 dark:text-gray-600"></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">No notifications to show</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>