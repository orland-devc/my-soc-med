<x-layouts.app :title="__('Notifications')">
    <div class="relative">

        <div class="max-w-2xl mx-auto md:border md:rounded-2xl md:p-4">
            <!-- Mobile Header -->
            <div class=" flex items-center justify-between mb-6">
                <h1 class="text-2xl flex items-center font-bold text-zinc-900 dark:text-white">
                    {{ __('Notifications') }}
                </h1>
                <livewire:auth.user-options/>
            </div>
        </div>

        
    </div>
</x-layouts.app>