<?php

use App\Models\User;
use Livewire\Volt\Component;

new class extends Component {
    public User $user;
};
?>

<div x-data="{ userOptions: false, logoutConfirmation: false }">
    <div>
        <a 
            @click="userOptions=true"
            class="cursor-pointer flex items-center p-2 hover:bg-zinc-200 dark:hover:bg-zinc-800 active:bg-zinc-200 dark:active:bg-zinc-800 rounded-full transition-all"
        >
            <i class="fa-solid fa-bars text-2xl"></i>
        </a>
    </div>
    <div 
        x-show="userOptions"
        x-transition
        class="fixed inset-0 bg-black/80 flex justify-end items-center z-40"
    >
        <div 
            @click.away="userOptions = false"
            class="flex flex-col min-h-screen min-w-86 bg-white dark:bg-zinc-900"
        >
            <div class="text-xl font-bold px-2 py-3">
                {{__('Options')}}
            </div>
            <a href="{{ route('user.show', Auth::id())}}" class="flex items-center gap-2 p-2 border-y border-zinc-500">
                <img src="{{ asset(Auth::user()->profile_photo_path) }}" alt="" class="w-12 h-12 object-cover rounded-full border">
                <div class="flex flex-col">
                    <p class="text-xl font-bold">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-zinc-500">
                        View profile
                        <i class="fa-solid fa-chevron-right"></i>
                    </p>
                </div>
            </a>
            <a href="">
                Recent Activities
            </a>
            <a href="">
                Settings
            </a>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:menu.item 
                    @click="logoutConfirmation = true; userOptions = false"
                    icon="arrow-right-start-on-rectangle" 
                    class="w-full"
                >
                    {{ __('Log Out') }}
                </flux:menu.item>
            </form>
        </div>
    </div>

    <div 
        x-show="logoutConfirmation"
        x-transition
        class="fixed inset-0 bg-black/80 flex justify-center items-center z-50"
    >
        <div 
            @click.away="logoutConfirmation = false"
            class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-lg max-w-sm w-full"
        >
            <h2 class="text-xl font-bold mb-4">{{ __('Confirm Logout') }}</h2>
            <p class="mb-6">{{ __('Are you sure you want to log out?') }}</p>
            <div class="flex justify-end gap-4">
                <flux:button 
                    @click="logoutConfirmation = false"
                    variant="filled"
                >
                    {{ __('Cancel') }}
                </flux:button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button 
                        type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all"
                    >
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>