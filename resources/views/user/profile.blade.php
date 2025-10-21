<x-layouts.app :title="__('Flexy - ' . $user->name )">
    <div class="relative">
        <div class="relative">
            <div class="flex sm:w-full md:w-2/3 lg:w-160 flex-1 flex-col m-auto gap-4" x-data="{ posts: true, reposts: false, privates: false, saved: false, archived: false }" >
                <div class="lg:hidden flex items-center justify-between">
                    <a class="cursor-pointer flex items-center p-2 hover:bg-zinc-200 dark:hover:bg-zinc-800 active:bg-zinc-200 dark:active:bg-zinc-800 rounded-full transition-all" onclick="history.back()">
                        <i class="fa-solid fa-chevron-left text-xl"></i>
                    </a>
                    <flux:heading size="lg" level="2" class="text-center font-bold">
                        @if ($user->id == Auth::id())
                            {{ __('My profile') }}
                        @else
                            {{ __($user->name . '\'s profile') }}
                        @endif
                    </flux:heading>
                    <livewire:auth.user-options :user="$user" />
                </div>

                <div class="flex flex-col gap-2 justify-center">
                    <div class="flex flex-col items-center relative">
                        <div class="h-50 md:h-72 w-full overflow-hidden">
                            @if ($user->cover_path)
                                <img src="{{asset($user->cover_path)}}" alt="" class="h-full w-full object-cover">
                            @else
                                <div class="bg-zinc-200 dark:bg-zinc-500 h-full w-full"></div>
                            @endif       
                        </div>  
                        <div class="relative flex flex-col items-center">
                            <img src="{{asset($user->profile_photo_path)}}" alt="" class="h-40 w-40 object-cover border-3 border-white rounded-full mt-[-50%]">
                            @if ($user->id == Auth::id())
                                <div class="flex items-center justify-center rounded-full h-10 w-10 object-cover border-2 bg-white dark:bg-zinc-800 absolute bottom-2 right-2 cursor-pointer hover:bg-zinc-300 dark:hover:bg-zinc-700 transition-all">
                                    <i class="fa-regular fa-camera"></i>
                                </div>
                            @endif
                        </div> 
                        <div class="font-semibold text-xl">
                            {{$user->name}}
                        </div>
                    </div>

                    @if ($user->id == Auth::id())
                        <div class="flex items-center gap-3 justify-center">
                            <flux:button
                                variant="filled"
                            >
                                Edit profile
                                <i class="fa fa-pen"></i>
                            </flux:button>
                        </div>
                    @else
                        <div class="flex items-center gap-3 justify-center">
                            <livewire:users.follow :user="$user" />
                            <flux:button
                                variant="filled"
                            >
                                Message
                                <i class="fa fa-paper-plane rotate-45"></i>
                            </flux:button>
                        </div>
                    @endif

                    <div class="flex items-center justify-center mx-6 mt-3">
                        <div class="flex flex-1/3 flex-col items-center">
                            <span class="font-bold text-lg">{{ $user->posts()->count() }}</span>
                            <span class="text-sm text-zinc-500">Posts</span>
                        </div>
                        <div class="flex flex-1/3 flex-col items-center border-x-2">
                            <span class="font-bold text-lg">{{ $user->followers()->count() }}</span>
                            <span class="text-sm text-zinc-500">Followers</span>
                        </div>
                        <div class="flex flex-1/3 flex-col items-center">
                            <span class="font-bold text-lg">{{ $user->following()->count() }}</span>
                            <span class="text-sm text-zinc-500">Following</span>
                        </div>
                    </div>

                </div>

                <div class="flex flex-col gap-2 justify-center mt-3 border-b border-zinc-300 dark:border-zinc-500 pt-1 mx-[-15px]">
                    <div class="flex items-center gap-1 pt-1">
                        <div 
                            @click="
                                posts = true;
                                reposts = false;
                                privates = false;
                                saved = false;
                                archived = false;

                                postsTab.classList.add('text-black', 'dark:text-white', 'border-b-2', 'border-black', 'dark:border-white');
                                postsTab.classList.remove('text-zinc-500', );
                                repostsTab.classList.remove('text-black', 'dark:text-white', 'border-b-2', 'border-black', 'dark:border-white');
                                repostsTab.classList.add('text-zinc-500');
                                privateTab.classList.remove('text-black', 'dark:text-white', 'border-b-2', 'border-black', 'dark:border-white');
                                privateTab.classList.add('text-zinc-500');
                                savedTab.classList.remove('text-black', 'dark:text-white', 'border-b-2', 'border-black', 'dark:border-white');
                                savedTab.classList.add('text-zinc-500');
                                archiveTab.classList.remove('text-black', 'dark:text-white', 'border-b-2', 'border-black', 'dark:border-white');
                                archiveTab.classList.add('text-zinc-500');
                            "
                            id="posts-tab" 
                            class="flex flex-1 text-black border-b-2 border-black dark:border-white dark:text-white items-center justify-center gap-2 py-2 hover:text-zinc-800 dark:hover:text-white cursor-pointer">
                            <i class="fa-solid fa-pen"></i>
                            <span class="text-xs md:text-sm">Posts</span>
                        </div>
                        <div 
                            @click="
                                posts = false;
                                reposts = true;
                                privates = false;
                                saved = false;
                                archived = false;

                                postsTab.classList.remove('text-black', 'dark:text-white', 'border-b-2', 'border-black', 'dark:border-white');
                                postsTab.classList.add('text-zinc-500', );
                                repostsTab.classList.add('text-black', 'dark:text-white', 'border-b-2', 'border-black', 'dark:border-white');
                                repostsTab.classList.remove('text-zinc-500');
                                privateTab.classList.remove('text-black', 'dark:text-white', 'border-b-2', 'border-black', 'dark:border-white');
                                privateTab.classList.add('text-zinc-500');
                                savedTab.classList.remove('text-black', 'dark:text-white', 'border-b-2', 'border-black', 'dark:border-white');
                                savedTab.classList.add('text-zinc-500');
                                archiveTab.classList.remove('text-black', 'dark:text-white', 'border-b-2', 'border-black', 'dark:border-white');
                                archiveTab.classList.add('text-zinc-500');
                            " 
                            id="reposts-tab"
                            class="flex flex-1 text-zinc-500 items-center justify-center gap-2 py-2 hover:text-zinc-800 dark:hover:text-white cursor-pointer">
                            <i class="fa-solid fa-retweet"></i>
                            <span class="text-xs md:text-sm">Reposts</span>
                        </div>
                        @if ($user->id == Auth::id())
                            <div 
                                @click="
                                    posts = false;
                                    reposts = false;
                                    privates = true;
                                    saved = false;
                                    archived = false;

                                    postsTab.classList.remove('text-black', 'dark:text-white', 'border-b-2', 'border-black', 'dark:border-white');
                                    postsTab.classList.add('text-zinc-500', );
                                    repostsTab.classList.remove('text-black', 'dark:text-white', 'border-b-2', 'border-black', 'dark:border-white');
                                    repostsTab.classList.add('text-zinc-500');
                                    privateTab.classList.add('text-black', 'dark:text-white', 'border-b-2', 'border-black', 'dark:border-white');
                                    privateTab.classList.remove('text-zinc-500');
                                    savedTab.classList.remove('text-black', 'dark:text-white', 'border-b-2', 'border-black', 'dark:border-white');
                                    savedTab.classList.add('text-zinc-500');
                                    archiveTab.classList.remove('text-black', 'dark:text-white', 'border-b-2', 'border-black', 'dark:border-white');
                                    archiveTab.classList.add('text-zinc-500');
                                " 
                                id="private-tab"
                                class="flex flex-1 text-zinc-500 items-center justify-center gap-2 py-2 hover:text-zinc-800 dark:hover:text-white cursor-pointer">
                                <i class="fa-solid fa-lock"></i>
                                <span class="text-xs md:text-sm">Private</span>
                            </div>
                            <div 
                                @click="
                                    posts = false;
                                    reposts = false;
                                    privates = false;
                                    saved = true;
                                    archived = false;
                                    
                                    postsTab.classList.remove('text-black', 'dark:text-white', 'border-b-2', 'border-black', 'dark:border-white');
                                    postsTab.classList.add('text-zinc-500', );
                                    repostsTab.classList.remove('text-black', 'dark:text-white', 'border-b-2', 'border-black', 'dark:border-white');
                                    repostsTab.classList.add('text-zinc-500');
                                    privateTab.classList.remove('text-black', 'dark:text-white', 'border-b-2', 'border-black', 'dark:border-white');
                                    privateTab.classList.add('text-zinc-500');
                                    savedTab.classList.add('text-black', 'dark:text-white', 'border-b-2', 'border-black', 'dark:border-white');
                                    savedTab.classList.remove('text-zinc-500');
                                    archiveTab.classList.remove('text-black', 'dark:text-white', 'border-b-2', 'border-black', 'dark:border-white');
                                    archiveTab.classList.add('text-zinc-500');
                                " 
                                id="saved-tab"
                                class="flex flex-1 text-zinc-500 items-center justify-center gap-2 py-2 hover:text-zinc-800 dark:hover:text-white cursor-pointer">
                                <i class="fa-solid fa-bookmark"></i>
                                <span class="text-xs md:text-sm">Saved</span>
                            </div>
                            <div 
                                @click="
                                    posts = false;
                                    reposts = false;
                                    privates = false;
                                    saved = false;
                                    archived = true;

                                    postsTab.classList.remove('text-black', 'dark:text-white', 'border-b-2', 'border-black', 'dark:border-white');
                                    postsTab.classList.add('text-zinc-500', );
                                    repostsTab.classList.remove('text-black', 'dark:text-white', 'border-b-2', 'border-black', 'dark:border-white');
                                    repostsTab.classList.add('text-zinc-500');
                                    privateTab.classList.remove('text-black', 'dark:text-white', 'border-b-2', 'border-black', 'dark:border-white');
                                    privateTab.classList.add('text-zinc-500');
                                    savedTab.classList.remove('text-black', 'dark:text-white', 'border-b-2', 'border-black', 'dark:border-white');
                                    savedTab.classList.add('text-zinc-500');
                                    archiveTab.classList.add('text-black', 'dark:text-white', 'border-b-2', 'border-black', 'dark:border-white');
                                    archiveTab.classList.remove('text-zinc-500');
                                " 
                                id="archived-tab"
                                class="flex flex-1 text-zinc-500 items-center justify-center gap-2 py-2 hover:text-zinc-800 dark:hover:text-white cursor-pointer">
                                <i class="fa-solid fa-box-archive"></i>
                                <span class="text-xs md:text-sm">Archived</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div x-show="posts" class="grid auto-rows-min gap-4">
                    @forelse ($user->posts->whereIn('privacy', [0, 1])->sortByDesc('created_at') as $post)
                        <livewire:profiles.posts :post="$post" />
                    @empty
                        <p class="text-center text-zinc-500 dark:text-zinc-400">No posts to show.</p>
                    @endforelse
                    <div class="border-b mx-[-16px] dark:border-gray-600 my-2"></div>
                </div>

                <div x-show="reposts" class="grid auto-rows-min gap-4">
                    @forelse ($user->reposts->sortByDesc('created_at') as $repost)
                        <livewire:profiles.posts :post="$repost->post" />
                    @empty
                        <p class="text-center text-zinc-500 dark:text-zinc-400">No reposts to show.</p>
                    @endforelse
                    <div class="border-b mx-[-16px] dark:border-gray-600 my-2"></div>
                </div>

                <div x-show="privates" class="grid auto-rows-min gap-4">
                    @forelse ($user->posts->where('privacy', 2)->sortByDesc('created_at') as $post)
                        <livewire:profiles.posts :post="$post" />
                    @empty
                        <p class="text-center text-zinc-500 dark:text-zinc-400">No private posts to show.</p>
                    @endforelse
                    <div class="border-b mx-[-16px] dark:border-gray-600 my-2"></div>
                </div>

                <div x-show="saved" class="grid auto-rows-min gap-4">
                    @forelse ($user->savedPosts->sortByDesc('created_at') as $saved)
                        <livewire:profiles.posts :post="$saved->post" />
                    @empty
                        <p class="text-center text-zinc-500 dark:text-zinc-400">No saved posts to show.</p>
                    @endforelse
                    <div class="border-b mx-[-16px] dark:border-gray-600 my-2"></div>
                </div>

                <div x-show="archived" class="grid auto-rows-min gap-4">
                    @forelse ($user->posts->where('archived', true)->sortByDesc('created_at') as $post)
                        <livewire:profiles.posts :post="$post" />
                    @empty
                        <p class="text-center text-zinc-500 dark:text-zinc-400">No archived posts to show.</p>
                    @endforelse
                    <div class="border-b mx-[-16px] dark:border-gray-600 my-2"></div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

<style>
    i:hover {
        animation: fa-bounce 1s;
    }
</style>

<script>
    const postsTab = document.getElementById('posts-tab');
    const repostsTab = document.getElementById('reposts-tab');
    const privateTab = document.getElementById('private-tab');
    const savedTab = document.getElementById('saved-tab');
    const archiveTab = document.getElementById('archived-tab')
</script>