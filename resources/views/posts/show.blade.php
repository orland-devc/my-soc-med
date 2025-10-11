<x-layouts.app :title="__('' . $post->caption . ' - ' . $post->user->name)">
    <div class="relative mb-6 w-full">
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
            <div class="flex sm:w-full md:w-2/3 lg:w-160 flex-1 flex-col m-auto gap-4 rounded-xl border p-4">
            <div>
                <div class="flex justify-between items-center">
                    <div class="">
                        <a href="/user/{{ $post->user->id }}" class="font-semibold hover:underline ">
                            <img src="{{ asset('images/user-profile.jpg') }}" alt="user-profile" class="w-8 h-8 rounded-full inline me-2">
                            {{ $post->user->name }}
                        </a>
                        <small 
                            class="ml-2 text-zinc-500 dark:text-zinc-400"
                            title="{{ $post->created_at->format('M j, Y g:i A') }}"
                        >
                            @if ($post->created_at->diffInHours(now()) < 672)
                                {{ $post->created_at->diffForHumans() }}
                            @else
                                {{ $post->created_at->format('M j, Y g:i A') }}
                            @endif
                        </small>

                    </div>

                <livewire:posts.post-options :post="$post" />
                    
                </div>

                <p class="py-2 font-semibold text-xl">{{ $post->caption }}</p>
                <p>{!! nl2br(e($post->description)) !!}</p>
            </div>

            <livewire:posts.like :post="$post" />
                
            <livewire:posts.comment-box :post="$post" />

             <livewire:posts.comments :post="$post" />

            <div class="hidden mt-3 border-t pt-3">
                <input wire:model.defer="repostCaption" type="text" placeholder="Add a caption..." class="w-full border rounded p-2">
                <button wire:click="repost" class="mt-2 bg-green-600 text-white px-3 py-1 rounded">
                    Repost
                </button>
            </div>
        </div>
    </div>
</x-layouts.app>
