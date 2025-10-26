<x-layouts.app :title="__('Search')">
    <div class="relative">
        <div class="max-w-2xl mx-auto md:border md:rounded-2xl md:p-4">
            <!-- Mobile Header -->
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl flex items-center font-bold text-zinc-900 dark:text-white">
                    {{ __('Search') }}
                </h1>
                <livewire:auth.user-options/>
            </div>

            <!-- Search Input -->
            <div class="mb-6">
                <div class="relative">
                    <input 
                        type="text" 
                        placeholder="{{ __('Search posts, people, or topics...') }}" 
                        class="w-full pl-12 pr-4 py-3 border border-zinc-200 dark:border-zinc-700 rounded-xl bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white placeholder-zinc-500 dark:placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                    >
                    <i class="fas fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400 dark:text-zinc-500"></i>
                </div>
            </div>

            <!-- Quick Filters -->
            <div class="flex gap-2 mb-6 overflow-x-auto pb-2 scrollbar-hide">
                <button class="px-4 py-2 rounded-full bg-blue-500 text-white text-sm font-medium whitespace-nowrap">
                    All
                </button>
                <button class="px-4 py-2 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 text-sm font-medium whitespace-nowrap hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors">
                    People
                </button>
                <button class="px-4 py-2 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 text-sm font-medium whitespace-nowrap hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors">
                    Posts
                </button>
                <button class="px-4 py-2 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 text-sm font-medium whitespace-nowrap hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors">
                    Tags
                </button>
            </div>

            <!-- Recent Searches -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold text-zinc-900 dark:text-white">
                        {{ __('Recent') }}
                    </h2>
                    <button class="text-sm text-blue-500 hover:text-blue-600 font-medium">
                        {{ __('Clear all') }}
                    </button>
                </div>

                <div class="space-y-1">
                    <!-- Recent Search Item - User -->
                    <a href="#" class="flex items-center gap-3 p-3 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors group">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center text-white font-semibold">
                            JD
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-zinc-900 dark:text-white">Jane Doe</p>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">@janedoe</p>
                        </div>
                        <i class="fas fa-clock text-zinc-400 dark:text-zinc-500 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </a>

                    <!-- Recent Search Item - Hashtag -->
                    <a href="#" class="flex items-center gap-3 p-3 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors group">
                        <div class="w-10 h-10 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                            <i class="fas fa-hashtag text-zinc-600 dark:text-zinc-400"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-zinc-900 dark:text-white">#webdevelopment</p>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">12.5k posts</p>
                        </div>
                        <i class="fas fa-clock text-zinc-400 dark:text-zinc-500 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </a>

                    <!-- Recent Search Item - Query -->
                    <a href="#" class="flex items-center gap-3 p-3 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors group">
                        <div class="w-10 h-10 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                            <i class="fas fa-magnifying-glass text-zinc-600 dark:text-zinc-400"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-zinc-900 dark:text-white">Laravel tips and tricks</p>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">Search query</p>
                        </div>
                        <i class="fas fa-clock text-zinc-400 dark:text-zinc-500 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </a>

                    <!-- Recent Search Item - User -->
                    <a href="#" class="flex items-center gap-3 p-3 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors group">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-cyan-400 flex items-center justify-center text-white font-semibold">
                            AS
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-zinc-900 dark:text-white">Alex Smith</p>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">@alexsmith</p>
                        </div>
                        <i class="fas fa-clock text-zinc-400 dark:text-zinc-500 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </a>

                    <!-- Recent Search Item - Hashtag -->
                    <a href="#" class="flex items-center gap-3 p-3 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors group">
                        <div class="w-10 h-10 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                            <i class="fas fa-hashtag text-zinc-600 dark:text-zinc-400"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-zinc-900 dark:text-white">#tailwindcss</p>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">8.2k posts</p>
                        </div>
                        <i class="fas fa-clock text-zinc-400 dark:text-zinc-500 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </a>
                </div>
            </div>

            <!-- Trending Section (Optional) -->
            <div class="mt-8">
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-white mb-4">
                    {{ __('Trending') }}
                </h2>
                
                <div class="space-y-4">
                    <a href="#" class="block p-3 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-1">Trending in Tech</p>
                                <p class="font-semibold text-zinc-900 dark:text-white">#AI2025</p>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">24.8k posts</p>
                            </div>
                            <i class="fas fa-arrow-trend-up text-blue-500"></i>
                        </div>
                    </a>

                    <a href="#" class="block p-3 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-1">Trending in Design</p>
                                <p class="font-semibold text-zinc-900 dark:text-white">#UIDesign</p>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">15.3k posts</p>
                            </div>
                            <i class="fas fa-arrow-trend-up text-blue-500"></i>
                        </div>
                    </a>

                    <a href="#" class="block p-3 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-1">Trending Worldwide</p>
                                <p class="font-semibold text-zinc-900 dark:text-white">#CodeNewbie</p>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">9.7k posts</p>
                            </div>
                            <i class="fas fa-arrow-trend-up text-blue-500"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>