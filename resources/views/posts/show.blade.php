<x-layouts.app :title="__('' . $post->caption . ' - ' . $post->user->name)">
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
        
    <div class="relative">
        <div class="flex sm:w-full md:w-2/3 lg:w-160 flex-1 flex-col m-auto gap-2 rounded-xl md:border md:p-4">
            <div class="md:hidden flex items-center justify-between">
                <a class="cursor-pointer flex items-center p-2 hover:bg-zinc-200 dark:hover:bg-zinc-800 active:bg-zinc-200 dark:active:bg-zinc-800 rounded-full transition-all" onclick="history.back()">
                    <i class="fa-solid fa-chevron-left text-xl"></i>
                </a>
                <flux:heading size="lg" level="2" class="text-center font-bold">
                    {{ $post->user->name }}'s Post
                </flux:heading>
                <livewire:posts.post-options :post="$post" />
            </div>
            <div>
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <img src="{{ asset($post->user->profile_photo_path) }}" alt="user-profile" class="w-9 h-9 rounded-full inline me-2">
                        <div class="">
                            <a href="/user/{{ $post->user->id }}" class="flex font-semibold hover:underline mb-[-5px] truncate max-w-[10rem] sm:max-w-[12rem] md:max-w-[14rem] lg:max-w-full">
                                {{ $post->user->name }}
                            </a>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400"title="{{ $post->created_at->format('M j, Y g:i A') }}">
                                @if ($post->created_at->diffInHours(now()) < 672)
                                    {{ $post->created_at->diffForHumans() }}
                                @else
                                    {{ $post->created_at->format('M j, Y g:i A') }}
                                @endif

                                @if ($post->privacy == 0)
                                    <i class="fa-solid fa-earth-americas"></i>
                                @elseif ($post->privacy == 1)
                                    <i class="fa-solid fa-user-group"></i>
                                @elseif ($post->privacy == 2)
                                    <i class="fa-solid fa-lock"></i>
                                @endif
                            </p>
                        </div>
                    </div>

                    <livewire:posts.post-options :post="$post" />
                    
                </div>

                <p class="pt-2 font-semibold text-xl">{{ $post->caption }}</p>
                <p>{!! nl2br(e($post->description)) !!}</p>
            </div>

            @if ($post->attachments()->count() >= 1)
                <livewire:attachments.post :post="$post" />
            @endif

            <livewire:posts.like :post="$post" />
                
            {{-- <div class="hidden md:block">
                <div id="filePreview" class="mb-4"></div>
                <livewire:posts.comment-box :post="$post" />
            </div> --}}

            <livewire:posts.comments :post="$post" />

            <div class="fixed md:static bottom-0 pb-2 right-4 left-4 bg-white dark:bg-zinc-900">
                <div id="filePreview"></div>
                <livewire:posts.comment-box :post="$post" />
            </div>

            <div class="h-10"></div>
        </div>
    </div>
</x-layouts.app>

<style>
    .hide-header {
        display: none;
    }
</style>