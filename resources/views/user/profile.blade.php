<x-layouts.app :title="__('Flexy - ' . $user->name )">
    <div class="relative">
        <div class="relative">
            <div class="flex sm:w-full md:w-2/3 lg:w-160 flex-1 flex-col m-auto gap-4" x-data="{ activeTab: 'posts' }">
                <!-- Header - Mobile Only -->
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

                <!-- Profile Header -->
                <div class="flex flex-col gap-4">
                    <!-- Cover Photo -->
                    <div class="relative -mx-4 sm:mx-0 sm:rounded-2xl">
                        <div class="h-48 md:h-64 w-full">
                            @if ($user->cover_path)
                                <img src="{{asset($user->cover_path)}}" alt="Cover" class="h-full w-full object-cover">
                            @else
                                <div class="bg-gradient-to-br from-blue-400 to-purple-500 dark:from-blue-600 dark:to-purple-700 h-full w-full"></div>
                            @endif
                        </div>

                        <!-- Profile Picture -->
                        <div class="absolute -bottom-16 left-1/2 transform -translate-x-1/2 md:left-8 md:translate-x-0 z-20">
                            <div class="relative">
                                <img src="{{asset($user->profile_photo_path)}}" alt="{{$user->name}}" class="h-32 w-32 object-cover border-4 border-white dark:border-zinc-900 rounded-full shadow-xl">
                                @if ($user->id == Auth::id())
                                    <button class="absolute bottom-0 right-0 flex items-center justify-center rounded-full h-10 w-10 bg-white dark:bg-zinc-800 border-2 border-zinc-200 dark:border-zinc-700 cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-all shadow-lg">
                                        <i class="fa-regular fa-camera text-sm"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Profile Info -->
                    <div class="flex flex-col gap-4 mt-16 md:mt-4 px-4 md:px-0">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            <div class="flex flex-col items-center md:items-start md:ml-44">
                                <h1 class="font-bold text-2xl text-gray-900 dark:text-white">{{$user->name}}</h1>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">@{{$user->username ?? 'user'}}</p>
                            </div>

                            <!-- Action Buttons -->
                            @if ($user->id == Auth::id())
                                <div class="flex items-center gap-2 justify-center md:justify-end">
                                    <flux:button variant="filled" class="flex items-center gap-2">
                                        <span>Edit profile</span>
                                        <i class="fa fa-pen text-sm"></i>
                                    </flux:button>
                                </div>
                            @else
                                <div class="flex items-center gap-2 justify-center md:justify-end">
                                    <livewire:users.follow :user="$user" />
                                    <flux:button variant="filled" class="flex items-center gap-2">
                                        <span>Message</span>
                                        <i class="fa fa-paper-plane text-sm"></i>
                                    </flux:button>
                                </div>
                            @endif
                        </div>

                        <!-- Stats -->
                        <div class="flex items-center justify-center md:justify-start gap-6 py-4 border-y border-zinc-200 dark:border-zinc-800">
                            <div class="w-64 flex items-center">
                                <div class="flex flex-col flex-1 items-center">
                                    <span class="font-bold text-xl text-gray-900 dark:text-white">{{ $user->posts()->count() }}</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Posts</span>
                                </div>
                                <div class="flex flex-col flex-1 items-center">
                                    <span class="font-bold text-xl text-gray-900 dark:text-white">{{ $user->followers()->count() }}</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Followers</span>
                                </div>
                                <div class="flex flex-col flex-1 items-center">
                                    <span class="font-bold text-xl text-gray-900 dark:text-white">{{ $user->following()->count() }}</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Following</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs Navigation -->
                <div class="sticky top-0 bg-white dark:bg-zinc-900 z-10 border-b border-zinc-200 dark:border-zinc-800 -mx-4 px-4">
                    <div class="flex items-center overflow-x-auto scrollbar-hide">
                        <button @click="activeTab = 'posts'" 
                            class="flex-1 min-w-fit flex items-center justify-center gap-2 py-3 px-4 transition-all"
                            :class="activeTab === 'posts' ? 'text-black dark:text-white border-b-2 border-black dark:border-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'">
                            <i class="fa-solid fa-pen text-sm"></i>
                            <span class="text-sm font-medium">Posts</span>
                        </button>
                        
                        <button @click="activeTab = 'reposts'" 
                            class="flex-1 min-w-fit flex items-center justify-center gap-2 py-3 px-4 transition-all"
                            :class="activeTab === 'reposts' ? 'text-black dark:text-white border-b-2 border-black dark:border-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'">
                            <i class="fa-solid fa-retweet text-sm"></i>
                            <span class="text-sm font-medium">Reposts</span>
                        </button>

                        @if ($user->id == Auth::id())
                            <button @click="activeTab = 'privates'" 
                                class="flex-1 min-w-fit flex items-center justify-center gap-2 py-3 px-4 transition-all"
                                :class="activeTab === 'privates' ? 'text-black dark:text-white border-b-2 border-black dark:border-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'">
                                <i class="fa-solid fa-lock text-sm"></i>
                                <span class="text-sm font-medium">Private</span>
                            </button>

                            <button @click="activeTab = 'saved'" 
                                class="flex-1 min-w-fit flex items-center justify-center gap-2 py-3 px-4 transition-all"
                                :class="activeTab === 'saved' ? 'text-black dark:text-white border-b-2 border-black dark:border-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'">
                                <i class="fa-solid fa-bookmark text-sm"></i>
                                <span class="text-sm font-medium">Saved</span>
                            </button>

                            <button @click="activeTab = 'archived'" 
                                class="flex-1 min-w-fit flex items-center justify-center gap-2 py-3 px-4 transition-all"
                                :class="activeTab === 'archived' ? 'text-black dark:text-white border-b-2 border-black dark:border-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'">
                                <i class="fa-solid fa-box-archive text-sm"></i>
                                <span class="text-sm font-medium">Archived</span>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="pb-8">
                    <!-- Posts Tab -->
                    <div x-show="activeTab === 'posts'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="grid auto-rows-min gap-4">
                        @forelse ($user->posts->whereIn('privacy', [0, 1])->sortByDesc('created_at') as $post)
                            <livewire:profiles.posts :post="$post" />
                        @empty
                            <div class="px-6 py-16 text-center">
                                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-zinc-800 flex items-center justify-center">
                                    <i class="fas fa-pen text-3xl text-gray-400 dark:text-gray-600"></i>
                                </div>
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-2">No posts yet</h3>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">When posts are shared, they'll appear here.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Reposts Tab -->
                    <div x-show="activeTab === 'reposts'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="grid auto-rows-min gap-4">
                        @forelse ($user->reposts->sortByDesc('created_at') as $repost)
                            <livewire:profiles.posts :post="$repost->post" />
                        @empty
                            <div class="px-6 py-16 text-center">
                                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-zinc-800 flex items-center justify-center">
                                    <i class="fas fa-retweet text-3xl text-gray-400 dark:text-gray-600"></i>
                                </div>
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-2">No reposts yet</h3>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Reposted content will appear here.</p>
                            </div>
                        @endforelse
                    </div>

                    @if ($user->id == Auth::id())
                        <!-- Private Posts Tab -->
                        <div x-show="activeTab === 'privates'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="grid auto-rows-min gap-4">
                            @forelse ($user->posts->where('privacy', 2)->sortByDesc('created_at') as $post)
                                <livewire:profiles.posts :post="$post" />
                            @empty
                                <div class="px-6 py-16 text-center">
                                    <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-zinc-800 flex items-center justify-center">
                                        <i class="fas fa-lock text-3xl text-gray-400 dark:text-gray-600"></i>
                                    </div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">No private posts</h3>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Your private posts will appear here.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Saved Posts Tab -->
                        <div x-show="activeTab === 'saved'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="grid auto-rows-min gap-4">
                            @forelse ($user->savedPosts->sortByDesc('created_at') as $saved)
                                <livewire:profiles.posts :post="$saved->post" />
                            @empty
                                <div class="px-6 py-16 text-center">
                                    <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-zinc-800 flex items-center justify-center">
                                        <i class="fas fa-bookmark text-3xl text-gray-400 dark:text-gray-600"></i>
                                    </div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">No saved posts</h3>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Save posts to view them later.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Archived Posts Tab -->
                        <div x-show="activeTab === 'archived'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="grid auto-rows-min gap-4">
                            @forelse ($user->posts->where('archived', true)->sortByDesc('created_at') as $post)
                                <livewire:profiles.posts :post="$post" />
                            @empty
                                <div class="px-6 py-16 text-center">
                                    <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-zinc-800 flex items-center justify-center">
                                        <i class="fas fa-box-archive text-3xl text-gray-400 dark:text-gray-600"></i>
                                    </div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">No archived posts</h3>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Archive posts to hide them from your profile.</p>
                                </div>
                            @endforelse
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    i:hover {
        animation: fa-bounce 1s;
    }
</style>