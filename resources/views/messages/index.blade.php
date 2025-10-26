<x-layouts.app :title="__('Inbox')">
    <div class="relative">
        <div class="max-w-2xl mx-auto md:border md:rounded-2xl md:p-4">
            <!-- Mobile Header -->
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl flex items-center font-bold text-zinc-900 dark:text-white">
                    {{ __('Inbox') }}
                </h1>
                <livewire:auth.user-options/>
            </div>

            <!-- Search Input -->
            <div class="mb-6">
                <div class="relative">
                    <input 
                        type="text" 
                        placeholder="{{ __('Search people, or message...') }}" 
                        class="w-full pl-12 pr-4 py-3 border border-zinc-200 dark:border-zinc-700 rounded-xl bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white placeholder-zinc-500 dark:placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                    >
                    <i class="fas fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400 dark:text-zinc-500"></i>
                </div>
            </div>

            <div class="px-6 py-16 text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-zinc-800 flex items-center justify-center">
                    <i class="fas fa-retweet text-3xl text-gray-400 dark:text-gray-600"></i>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-white mb-2">No recent searches</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Search history will appear here.</p>
            </div>
        </div>
    </div>
</x-layouts.app>