<x-layouts.app :title="__('' . $post->caption . ' - ' . $post->user->name)">
    <div class="relative">
        <div class="flex sm:w-full md:w-2/3 lg:w-160 flex-1 flex-col m-auto gap-2">
            <div class="lg:hidden flex items-center justify-between">
                <a class="cursor-pointer flex items-center p-2 hover:bg-zinc-200 dark:hover:bg-zinc-800 active:bg-zinc-200 dark:active:bg-zinc-800 rounded-full transition-all" onclick="history.back()">
                    <i class="fa-solid fa-chevron-left text-xl"></i>
                </a>
                <flux:heading size="lg" level="2" class="text-center font-bold">
                    {{ $post->user->id == Auth::user()->id ?  'Your' : $post->user->name . "'s" }} Post
                </flux:heading>
                <livewire:posts.post-options :post="$post" />
            </div>
            <div class="bg-white dark:bg-zinc-900 rounded-xl md:shadow-sm md:border border-zinc-200 dark:border-zinc-800 overflow-hidden hover:shadow-md transition-shadow">
                <!-- Post Header -->
                <div class="md:px-4 py-3 border-b border-zinc-100 dark:border-zinc-800">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-3 flex-1">
                            <a href="/user/{{ $post->user->id }}" class="flex-shrink-0">
                                <img 
                                    src="{{ asset($post->user->profile_photo_path) }}" 
                                    alt="{{ $post->user->name }}" 
                                    class="w-11 h-11 rounded-full object-cover border-2 border-zinc-200 dark:border-zinc-700 hover:border-blue-400 transition-colors"
                                >
                            </a>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <a href="/user/{{ $post->user->id }}" class="font-semibold text-zinc-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                        {{ $post->user->name }}
                                    </a>
                                    @if ($post->user->popular)
                                        <img src="{{asset('images/image.png')}}" alt="verified" class="h-4 w-4">
                                    @endif
                                    <span class="text-xs font-medium px-2 py-1 bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 rounded-full">
                                        @if ($post->privacy == 0)
                                            <i class="fa-solid fa-earth-americas mr-1"></i>
                                        @elseif ($post->privacy == 1)
                                            <i class="fa-solid fa-user-group mr-1"></i>
                                        @elseif ($post->privacy == 2)
                                            <i class="fa-solid fa-lock mr-1"></i>
                                        @endif
                                    </span>
                                </div>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1" title="{{ $post->created_at->format('M j, Y g:i A') }}">
                                    @if ($post->created_at->diffInHours(now()) < 672)
                                        {{ $post->created_at->diffForHumans() }}
                                    @else
                                        {{ $post->created_at->format('M j, Y') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="hidden lg:block">
                            <livewire:posts.post-options :post="$post" />
                        </div>
                    </div>
                </div>

                <!-- Post Content -->
                <div class="md:px-4 py-4">
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-2">
                        {{ $post->caption }}
                    </h3>
                    <p class="text-zinc-700 dark:text-zinc-300 text-sm leading-relaxed line-clamp-4">
                        {!! nl2br(e($post->description)) !!}
                    </p>
                </div>

                <!-- Post Attachments -->
                @if ($post->attachments()->count() >= 1)
                    <livewire:attachments.post :post="$post" />
                @endif

                <!-- Engagement Stats & Actions -->
                <div class="md:px-4 py-3">
                    <livewire:posts.like :post="$post" />
                </div>

                <div class="md:px-4">
                    <livewire:posts.comments :post="$post" />
                </div>

                <!-- Comment Box -->
                <div class="fixed md:static bottom-0 pb-2 md:px-4 right-4 left-4 bg-white dark:bg-zinc-900">
                    <div id="filePreview"></div>
                    <livewire:posts.comment-box :post="$post" />
                </div>
                <div class="h-10 md:hidden"></div>
            </div>
        </div>
    </div>
</x-layouts.app>

<style>
    .hide-header {
        display: none;
    }
</style>