<x-layouts.app :title="__('Inbox')">
    <div class="relative">
        <div class="relative mb-6 w-full hidden md:block">
            <div class="flex justify-between">
                <div class="">
                    <flux:heading size="xl" level="1">{{ __('Public Posts') }}</flux:heading>
                    <flux:subheading size="lg" class="mb-6">{{ __('View and interact with public posts and announcements.') }}</flux:subheading>
                </div>
                <div class=" flex items-center">
                    <a href="#" onclick="openForm()" class="font-semibold dark:bg-white text-white dark:text-black dark:hover:bg-zinc-300 bg-zinc-700 hover:bg-zinc-600/75 transition-all px-2 py-3 rounded-lg">
                        <div class="hidden md:flex items-center gap-2">
                            <ion-icon name="add" class="text-xl"></ion-icon>
                            <span class="mr-2">Create Post</span>
                        </div>
                        <div class="md:hidden flex items-center gap-2">
                            <ion-icon name="add" class="text-xl "></ion-icon>
                            <p class="mr-2">New</p>
                        </div>
                    </a>
                </div>
            </div>
            <flux:separator variant="subtle" />
        </div>

        <div class=""> 
            {{-- show nothing here yet --}}
            <i class="fa-brands fa-facebook-messenger"></i>
            <i data-lucide="heart"></i>
            <i data-lucide="user"></i>
        </div>
    </div>
</x-layouts.app>