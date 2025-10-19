<x-layouts.app :title="__('Notifications')">
    <div class="relative">
        <div class="relative mb-6 w-full hidden md:block">
            <div class="flex justify-between">
                <div class="">
                    <flux:heading size="xl" level="1">{{ __('Notifications') }}</flux:heading>
                    {{-- <flux:subheading size="lg" class="mb-6">{{ __('') }}</flux:subheading> --}}
                </div>
            </div>

            <flux:separator variant="subtle" />
            
        </div>


        <div class="relative">
            <div class="flex sm:w-full md:w-2/3 lg:w-160 flex-1 flex-col m-auto gap-4">
                <div class="md:hidden flex items-center justify-between">
                    <flux:heading size="lg" level="2" class="text-center font-bold">
                        {{ __('Notifications') }}
                    </flux:heading>
                    <img src="{{ asset(Auth::user()->profile_photo_path) }}" alt="" class="w-7 h-7 rounded-full inline border">
                </div>
                
            </div>
        </div>

        
    </div>
</x-layouts.app>