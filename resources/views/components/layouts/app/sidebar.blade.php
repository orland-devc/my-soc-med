<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-900">
    <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
            <x-app-logo />
        </a>

        <flux:navlist variant="outline">
            {{-- <flux:navlist.group :heading="__('Platform')" class="grid">
                <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </flux:navlist.item>

            </flux:navlist.group> --}}

            <flux:navlist.group :heading="__('Platform')" class="grid">

                <flux:navlist.item icon="house" :href="route('posts')" :current="request()->routeIs('posts*')" wire:navigate>
                    {{ __('Posts') }}
                </flux:navlist.item>

                <flux:navlist.item icon="message-circle" :href="route('inbox')" :current="request()->routeIs('inbox*')" wire:navigate>
                    {{ __('Inbox') }}
                </flux:navlist.item>

                <flux:navlist.item icon="square-plus" :href="route('new')" :current="request()->routeIs('new*')" wire:navigate>
                    {{ __('New Post') }}
                </flux:navlist.item>

                <flux:navlist.item icon="bell" :href="route('notifications')" :current="request()->routeIs('notifications*')" wire:navigate>
                    {{ __('Notifications') }}
                </flux:navlist.item>

                <flux:navlist.item icon="user-circle" :href="route('user.show', Auth::user()->id)"
                    :current="request()->routeIs('user.show') && request()->route('id') == Auth::user()->id" wire:navigate>
                    {{ __('Profile') }}
                </flux:navlist.item>


            </flux:navlist.group>

        </flux:navlist>

        <flux:spacer />

        <flux:navlist variant="outline">
            <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit"
                target="_blank">
                {{ __('Repository') }}
            </flux:navlist.item>

            <flux:navlist.item icon="book-open" href="https://laravel.com/docs/starter-kits#livewire"
                target="_blank">
                {{ __('Documentation') }}
            </flux:navlist.item>
        </flux:navlist>

        <!-- Desktop User Menu -->
        <flux:dropdown position="bottom" align="start">
            <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                icon-trailing="chevrons-up-down" />

            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
                        {{ __('Settings') }}</flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:mobile-header stashable sticky class="hide-header border-b lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">

        {{-- <a href="{{ route('dashboard') }}" class="hover:bg-zinc-100 dark:hover:bg-zinc-800 ms-1 px-3 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
            <div class="flex aspect-square size-8 items-center justify-center">
                <x-app-logo-icon class="size-5" />
            </div>
        </a> --}}

        <flux:mobile-navbar class="-mb-px text-2xl">
            <flux:mobile-navbar.item icon="house" :href="route('posts')" :current="request()->routeIs('posts*')" wire:navigate>
            </flux:mobile-navbar.item>
        </flux:mobile-navbar>

        {{-- <flux:mobile-navbar class="-mb-px text-2xl">
            <flux:mobile-navbar.item icon="search" :href="route('search')" :current="request()->routeIs('search*')" wire:navigate>
            </flux:mobile-navbar.item>
        </flux:mobile-navbar> --}}

        <flux:mobile-navbar class="-mb-px text-2xl">
            <flux:mobile-navbar.item icon="message-circle" :href="route('inbox')" :current="request()->routeIs('inbox*')" wire:navigate>
            </flux:mobile-navbar.item>
        </flux:mobile-navbar>

        <flux:mobile-navbar class="-mb-px text-2xl">
            <flux:mobile-navbar.item icon="circle-plus" :href="route('new')" :current="request()->routeIs('new')" wire:navigate>
            </flux:mobile-navbar.item>
        </flux:mobile-navbar>

        <flux:mobile-navbar class="-mb-px text-2xl">
            <flux:mobile-navbar.item icon="users" :href="route('notifications')" :current="request()->routeIs('notifications')" wire:navigate>
            </flux:mobile-navbar.item>
        </flux:mobile-navbar>

<flux:mobile-navbar class="-mb-px text-2xl">
    <flux:mobile-navbar.item
        :image="Auth::user()->profile_photo_path"
        :href="route('user.show', Auth::user()->id)"
        :current="request()->routeIs('user.show') && request()->route('id') == Auth::user()->id"
        wire:navigate
    />
</flux:mobile-navbar>



        {{-- <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" /> --}}


        {{-- <flux:spacer /> --}}

        {{-- <flux:tooltip :content="__('Search')" position="bottom">
            <flux:navbar.item class="!h-10 [&>div>svg]:size-5 active:bg-zinc-200 dark:active:bg-zinc-700 transition-all" icon="magnifying-glass" href="#"/>
        </flux:tooltip> --}}
    </flux:mobile-header>


    {{-- <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
                        {{ __('Settings') }}</flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header> --}}

    {{ $slot }}

    @fluxScripts
</body>

</html>
