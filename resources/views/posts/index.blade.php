<x-layouts.app :title="__('Posts')">
    <div class="relative flex flex-col gap-3">
        <div class="relative mb-6 w-full hidden lg:block">
            <div class="flex justify-between">
                <div class="">
                    <flux:heading size="xl" level="1">{{ __('Public Posts') }}</flux:heading>
                    <flux:subheading size="lg" class="mb-6">{{ __('View and interact with public posts and announcements.') }}</flux:subheading>
                </div>
                <div class=" flex items-center">
                    <a href="{{route('new')}}" wire:navigate class="font-semibold dark:bg-white text-white dark:text-black dark:hover:bg-zinc-300 bg-zinc-700 hover:bg-zinc-600/75 transition-all px-2 py-3 rounded-lg">
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

        <div class="flex items-center justify-between mt-[-10px] lg:hidden">
            <img src="{{ asset('images/flexy/branding-dark.png') }}" alt="" class="h-10 hidden dark:block">
            <img src="{{ asset('images/flexy/branding.png') }}" alt="" class="h-10 dark:hidden">
            <i class="fas fa-magnifying-glass text-lg"></i>
        </div>


        <div class="">
            <div class="flex sm:w-full md:w-2/3 lg:w-160 flex-1 flex-col m-auto gap-4">
                <div class="flex flex-col gap-1 mb-2">
                    {{-- <p>Flexed Today</p> --}}
                    <div class="flex gap-3 ">
                        <div class="relative flex flex-col items-center h-15 w-15">
                            <img src="{{ Auth::user()->profile_photo_path }}" alt="" class="rounded-full h-15 w-15 object-cover">
                            <small class="">Your flex</small>
                            <div class="absolute border border-zinc-300 dark:border-zinc-600 w-full h-full rounded-full flex items-center justify-center bg-black/30 cursor-pointer hover:bg-black/40 transition-all" onclick="openForm()">
                                <i class="fas fa-plus text-xl text-white"></i>
                            </div>
                        </div>

                        <div class="relative flex flex-col items-center w-15">
                            <div class="border-2 border-orange-400 rounded-full h-15 w-15 flex items-center justify-center">
                                <img src="{{asset('images/uploads/unnamed.jpg')}}" alt="" class="rounded-full max-h-13 max-w-13 object-cover">
                            </div>
                            <div class="flex items-center w-full">
                                <small class="truncate">Daniel John Padilla</small>
                                <img src="{{asset('images/image.png')}}" alt="" class="h-4">
                            </div>
                            {{-- <div class="absolute border border-zinc-300 dark:border-zinc-600 w-full h-full rounded-full flex items-center justify-center bg-black/30 cursor-pointer hover:bg-black/40 transition-all" onclick="openForm()">
                                <i class="fas fa-plus text-xl text-white"></i>
                            </div> --}}
                        </div>
                    </div>
                </div>

                <div class="grid auto-rows-min gap-4">
                    @forelse ($posts as $post)
                        <div class="border-b-1 mx-[-16px] dark:border-gray-600"></div>
                        <div class="flex flex-col gap-2">
                            <div>
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <img src="{{ asset($post->user->profile_photo_path) }}" alt="user-profile" class="w-9 h-9 rounded-full inline me-2 object-cover">
                                        <div>
                                            <a href="/user/{{ $post->user->id }}" class="flex items-center gap-1 font-semibold hover:underline mb-[-5px]">
                                                {{ $post->user->name }}
                                                @if ($post->user->popular)
                                                    <img src="{{asset('images/image.png')}}" alt="" class="h-4">
                                                @endif
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
                                <p class="py-2 font-semibold text-xl">{{ $post->caption }}</p>
                                <p>{!! nl2br(e($post->description)) !!}</p>
                            </div>

                            {{-- @foreach ($post->attachments as $attachment)
                                <div class="flex mx-[-15px]">
                                    <img src="{{ asset($attachment->file_location) }}" alt="{{ $attachment->file_name }}" class="max-h-[40rem] lg:max-h-[54rem] min-w-full object-cover">
                                </div>
                            @endforeach --}}

                            @if ($post->attachments()->count() >= 1)
                                <livewire:attachments.post :post="$post" />
                            @endif

                            <livewire:posts.like :post="$post" />   

                            @php
                                $recentComment = $post->recentComment();
                            @endphp

                            @if($recentComment)
                                <a href="{{ route('posts.show', $post->id) }}" class="flex mt-4 gap-2">
                                    <img src="{{ asset($recentComment->user->profile_photo_path) }}" alt="user-profile" class="w-8 h-8 rounded-full inline mt-1 border">
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

                        <livewire:posts.comment-box :post="$post" />

                    @empty
                        
                    @endforelse
                </div>
            </div>
        </div>

        {{-- <div id="postForm" class="absolute inset-0 hidden z-100">
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
                            
                            <input type="file" name="" id="">

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
        </div> --}}
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