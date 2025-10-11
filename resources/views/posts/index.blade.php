<x-layouts.app :title="__('Posts')">
    <div class="relative">
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
            <div class="flex sm:w-full md:w-2/3 lg:w-160 flex-1 flex-col m-auto gap-4 rounded-xl">
                <div class="grid auto-rows-min gap-4">
                    @forelse ($posts as $post)
                        <div class="p-4">
                            <div>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <a href="/user/{{ $post->user->id }}" class="font-semibold hover:underline ">
                                            <img src="{{ asset('images/user-profile.jpg') }}" alt="user-profile" class="w-8 h-8 rounded-full inline me-2">
                                            {{ $post->user->name }}
                                        </a>
                                        <small 
                                            class="text-xs ml-2"
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

                            @php
                                $recentComment = $post->recentComment();
                            @endphp

                            @if($recentComment)
                                <a href="{{ route('posts.show', $post->id) }}" class="flex mt-4 gap-2">
                                    <img src="{{ asset('images/user-profile.jpg') }}" alt="user-profile" class="w-8 h-8 rounded-full inline mt-1">
                                    <div>
                                        <div class="w-full rounded-xl bg-zinc-100 dark:bg-zinc-700 px-3 py-1">
                                            <p class="font-semibold">
                                                <span class="text-zinc-800 dark:text-zinc-100 text-sm">{{ $recentComment->user->name }}</span>
                                                <small class="text-xs ml-2 text-zinc-500 dark:text-zinc-400">
                                                    @if ($recentComment->created_at->diffInHours(now()) < 24)
                                                        {{ $recentComment->created_at->diffForHumans() }}
                                                    @else
                                                        {{ $recentComment->created_at->format('M j, Y g:i A') }}
                                                    @endif
                                                </small>
                                            </p>
                                            <p class="text-zinc-800 dark:text-zinc-100 text-sm">{{ $recentComment->content }}</p>
                                        </div>
                                        <div class="ml-3 mt-2 text-sm flex gap-4">
                                            <button class="cursor-pointer flex items-center gap-1 hover:scale-110 transition-all">
                                                @if ($recentComment->likes->where('user_id', Auth::id())->count())
                                                    <i class="fa-solid fa-heart text-lg" style="color: #ff6961"></i>
                                                @else
                                                    <i class="far fa-heart text-lg"></i>
                                                @endif
                                                {{ $recentComment->likes->count() }}
                                            </button>

                                            <button onclick="showToast()" class="cursor-pointer flex items-center gap-1 hover:scale-110 transition-all">
                                                <p class="text-md mr-1">Reply</p>
                                            </button>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </div>

                        <flux:separator variant="subtle" />

                    @empty
                        
                    @endforelse
                </div>
            </div>
        </div>

        <div id="postForm" class="absolute inset-0 hidden">
            <div class="relative">
                <div class="flex items-center">
                    <div class="border sm:w-full md:w-1/2 m-auto p-4 rounded-xl dark:bg-zinc-900 bg-white shadow-xl">
                        <flux:heading size="xl" level="1">{{ __('New Post') }}</flux:heading>
                        <flux:subheading size="lg" class="mb-6">{{ __('Share your feelings and ideas to public.') }}</flux:subheading>
                        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-6">
                            @csrf
                            <flux:input
                                name="caption"
                                :label="__('Caption')"
                                type="text"
                            />

                            <flux:textarea
                                name="description"
                                :label="__('Description')"
                            />
                            
                            {{-- <input type="file" name="" id=""> --}}

                            <div class="flex items-center gap-4">
                                <div class="flex items-center justify-end gap-2">
                                    <flux:button onclick="closeForm()" class="w-full">{{ __('Cancel') }}</flux:button>
                                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Post') }}</flux:button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

<script>
    const postForm = document.getElementById('postForm');
    
    function openForm() {
        postForm.classList.remove('hidden');
    }

    function closeForm() {
        postForm.classList.add('hidden');
    }

</script>