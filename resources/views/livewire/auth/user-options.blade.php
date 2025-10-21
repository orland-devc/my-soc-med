<?php

use App\Models\User;
use Livewire\Volt\Component;

new class extends Component {
    public User $user;
};
?>

<div x-data="{ userOptions: false, logoutConfirmation: false }">
    <!-- Trigger Button -->
    <div>
        <button 
            @click="userOptions=true"
            class="cursor-pointer flex items-center justify-center p-2 hover:bg-zinc-200 dark:hover:bg-zinc-800 active:bg-zinc-300 dark:active:bg-zinc-700 rounded-full transition-all duration-200"
        >
            <i class="fa-solid fa-bars text-2xl text-zinc-700 dark:text-zinc-300"></i>
        </button>
    </div>

    <!-- Backdrop & Sidebar -->
    <div 
        x-show="userOptions"
        x-transition:enter="transition ease-out duration-150"
        x-transition:leave="transition ease-in duration-150"
        class="fixed inset-0 bg-black/50 flex justify-end z-40"
        @click="userOptions = false"
    >
        <div 
            @click.stop
            x-show="userOptions"
            x-transition:enter="transition ease-out duration-150 transform"
            x-transition:enter-start="translate-x-full"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-end="translate-x-full"
            class="flex flex-col w-80 h-screen bg-white dark:bg-zinc-900 shadow-2xl"
        >
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-200 dark:border-zinc-800">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Menu</h2>
                <button 
                    @click="userOptions = false"
                    class="p-1 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-all"
                >
                    <i class="fa-solid fa-xmark text-xl text-zinc-600 dark:text-zinc-400"></i>
                </button>
            </div>

            <!-- User Profile Card -->
            <a href="{{ route('user.show', Auth::id())}}" class="flex items-center gap-3 p-4 mx-3 mt-3 bg-gradient-to-br from-orange-700 to-blue-700 rounded-xl hover:shadow-md transition-shadow group">
                <img 
                    src="{{ asset(Auth::user()->profile_photo_path) }}" 
                    alt="{{ Auth::user()->name }}" 
                    class="w-14 h-14 object-cover rounded-lg border-2 border-blue-200 dark:border-blue-900 group-hover:border-blue-400 transition-colors"
                >
                <div class="flex flex-col flex-1">
                    <p class="font-semibold text-white text-sm">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-white">View your profile</p>
                </div>
                <i class="fa-solid fa-chevron-right text-white"></i>
            </a>

            <!-- Menu Items -->
            <nav class="flex-1 px-3 py-4 space-y-1">
                <a href="{{ route('user.show', Auth::id())}}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-all group">
                    <i class="fa-solid fa-user text-base w-5 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors"></i>
                    <span class="font-medium text-sm">{{ __('My Profile') }}</span>
                </a>

                <a href="" class="flex items-center gap-3 px-4 py-3 rounded-lg text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-all group">
                    <i class="fa-solid fa-clock text-base w-5 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors"></i>
                    <span class="font-medium text-sm">{{ __('Recent Activities') }}</span>
                </a>

                <a href="" class="flex items-center gap-3 px-4 py-3 rounded-lg text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-all group">
                    <i class="fa-solid fa-heart text-base w-5 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors"></i>
                    <span class="font-medium text-sm">{{ __('Saved Items') }}</span>
                </a>

                <a href="{{route('settings.profile')}}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-all group" wire:navigate>
                    <i class="fa-solid fa-cog text-base w-5 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors"></i>
                    <span class="font-medium text-sm">{{ __('Settings') }}</span>
                </a>

                <a href="" class="flex items-center gap-3 px-4 py-3 rounded-lg text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-all group">
                    <i class="fa-solid fa-bell text-base w-5 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors"></i>
                    <span class="font-medium text-sm">{{ __('Notifications') }}</span>
                    <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-0.5">3</span>
                </a>

                <a href="" class="flex items-center gap-3 px-4 py-3 rounded-lg text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-all group">
                    <i class="fa-solid fa-question-circle text-base w-5 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors"></i>
                    <span class="font-medium text-sm">{{ __('Help & Support') }}</span>
                </a>
            </nav>

            <!-- Divider -->
            <div class="border-t border-zinc-200 dark:border-zinc-800"></div>

            <!-- Logout Button -->
            <div class="p-3">
                <button 
                    @click="logoutConfirmation = true; userOptions = false"
                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-100 dark:hover:bg-red-950/50 transition-all font-medium text-sm"
                >
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    {{ __('Log Out') }}
                </button>
            </div>
            <div class="h-13"></div>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div 
        x-show="logoutConfirmation"
        x-transition:enter="transition ease-out duration-200"
        x-transition:leave="transition ease-in duration-150"
        class="fixed inset-0 bg-black/60 flex justify-center items-center z-50"
        @click="logoutConfirmation = false"
    >
        <div 
            @click.stop
            x-show="logoutConfirmation"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="scale-95 opacity-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-end="scale-95 opacity-0"
            class="bg-white dark:bg-zinc-900 p-6 rounded-xl shadow-xl max-w-sm w-full mx-4"
        >
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 dark:bg-red-950/30 rounded-full mb-4">
                <i class="fa-solid fa-triangle-exclamation text-red-600 dark:text-red-400 text-xl"></i>
            </div>
            <h2 class="text-xl font-bold text-center text-zinc-900 dark:text-white mb-2">{{ __('Confirm Logout') }}</h2>
            <p class="text-center text-zinc-600 dark:text-zinc-400 text-sm mb-6">{{ __('Are you sure you want to log out? You\'ll need to sign in again to access your account.') }}</p>
            <div class="flex gap-3">
                <button 
                    @click="logoutConfirmation = false"
                    class="flex-1 px-4 py-2 bg-zinc-200 dark:bg-zinc-700 text-zinc-900 dark:text-white rounded-lg hover:bg-zinc-300 dark:hover:bg-zinc-600 transition-all font-medium"
                >
                    {{ __('Cancel') }}
                </button>
                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button 
                        type="submit"
                        class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all font-medium"
                    >
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>