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
            <div class="flex items-center gap-2">
                <livewire:notifications.index/>
                <livewire:auth.user-options/>
            </div>
        </div>


        <div class="">
            <div class="flex sm:w-full md:w-2/3 lg:w-160 flex-1 flex-col m-auto gap-4">
                <div class="flex flex-col gap-1 mb-2">
                    {{-- <p>Flexed Today</p> --}}
                    <div class="flex gap-3 ">
                        <div class="flex flex-col items-center">
                            <div class="relative">
                                <img src="{{ Auth::user()->profile_photo_path }}" alt="" class="rounded-full h-15 w-15 object-cover">
                                <div class="absolute top-0 border border-zinc-300 dark:border-zinc-600 w-full h-full rounded-full flex items-center justify-center bg-black/30 cursor-pointer hover:bg-black/40 transition-all" onclick="openForm()">
                                    <i class="fas fa-plus text-xl text-white"></i>
                                </div>
                            </div>
                            <small class="">Your flex</small>
                        </div>

                        {{-- <div class="relative flex flex-col items-center w-15">
                            <div class="border-2 border-orange-400 rounded-full h-15 w-15 flex items-center justify-center">
                                <img src="{{asset('images/uploads/unnamed.jpg')}}" alt="" class="rounded-full max-h-13 max-w-13 object-cover">
                            </div>
                            <div class="flex items-center w-full">
                                <small class="truncate">Daniel John Padilla</small>
                                <img src="{{asset('images/image.png')}}" alt="" class="h-4">
                            </div>
                        </div> --}}
                    </div>
                </div>

                <div class="grid auto-rows-min gap-4">
                    @forelse ($posts as $post)
                        <livewire:profiles.posts :post="$post"/>

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