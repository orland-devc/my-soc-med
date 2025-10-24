<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout id="profileSection" :heading="__('Profile')" :subheading="__('Update your name and email address')">
        <livewire:settings.profile-picture/>
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        <livewire:settings.delete-user-form />

        <div x-data="{ open: false }" class="mt-10">
            <flux:button variant="danger" @click="open = true">{{ __('Deactivate Account') }}</flux:button>

            <div 
                x-show="open"
                {{-- what abot closing when `esc` is pushed? --}}
                @keydown.escape.window="open = false"
                x-transition
                class="fixed inset-0 bg-black/80 flex justify-center items-center z-50"
            >
                <div @click.away="open = false" class="bg-white sm:w-full md:w-2/3 lg:w-160 dark:bg-zinc-800 rounded-xl p-6 w-96 flex flex-col gap-2">
                    <h2 class="text-lg font-semibold mb-2">Deactivate Account?</h2>
                    <flux:text>
                        Are you sure you want to deactivate your account? All your data will be hidden and you can reactivate your account by logging back in.
                    </flux:text>
                    <div class="flex flex-col gap-3 mt-4 p-3">
                        <form method="POST" action="{{ route('account.deactivate') }}" class="flex flex-col gap-3">
                            @csrf
                            <flux:input type=password name="password" required :label="__('Enter password tp confirm')" placeholder="{{ __('Password') }}" viewable/>
                            <div class="flex items-center justify-end gap-2">
                                <a @click="open = false" class="text-sm cursor-pointer px-3 py-2 bg-zinc-500 dark-bg-gray-300 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-all">Cancel</a>
                                <flux:button variant="danger" type="submit">{{ __('Deactivate Account') }}</flux:button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </x-settings.layout>
</section>
