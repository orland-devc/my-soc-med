<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {

};

?>

<div class="flex flex-col items-center gap-4 mt-4" x-data="{profileModal: false}">
    <div class="flex">
        <a 
            @click="
                profileModal = true;
            "
            class="border-2 w-33 h-33 rounded-full border-zinc-300 dark:border-zinc-600 active:border-orange-400 cursor-pointer"
        >
            <img 
                src="{{ asset(Auth::user()->profile_photo_path) }}" 
                class="rounded-full w-32 h-32 object-cover border-2 border-zinc-300 dark:border-zinc-600"
                alt="Profile photo"
            >   
        </a>         
    </div>

    <div 
        x-show="profileModal"
        @click.away="profileModal = false"
        x-transition
        x-on:close-profile-modal.window="profileModal = false"
        class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center">

        <div 
            x-data="{
                preview: null,
                fileChosen(event) {
                    const file = event.target.files[0];
                    if (!file) return;
                    this.preview = URL.createObjectURL(file);
                }
            }"
            class="rounded-xl p-6 flex flex-col gap-4 bg-white dark:bg-zinc-900 border-2 border-zinc-200 dark:border-zinc-700 max-w-sm w-full"
        >
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white text-center">
                {{ __('Update Profile Picture') }}
            </h2>

            <!-- Clickable Frame -->
            <form action="{{route('user.update_picture')}}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                @csrf

                <input type="hidden" name="user_id" value="{{Auth::user()->id}}">

                <label 
                    for="profilePicture"
                    class="relative flex items-center justify-center w-40 h-40 mx-auto border-4 border-dashed border-zinc-300 dark:border-zinc-600 rounded-full cursor-pointer hover:border-blue-400 dark:hover:border-blue-500 transition-all overflow-hidden group"
                >
                    <template x-if="preview">
                        <img 
                            :src="preview" 
                            alt="Preview" 
                            class="object-cover w-full h-full rounded-full"
                        >
                    </template>

                    <template x-if="!preview">
                        <div class="flex flex-col items-center justify-center text-center p-4">
                            <i class="fas fa-camera text-3xl text-zinc-500 group-hover:text-blue-500"></i>
                            <span class="text-sm text-zinc-500 dark:text-zinc-400 mt-2">
                                Click to choose image
                            </span>
                        </div>
                    </template>
                </label>

                <!-- File Input (hidden) -->
                <input 
                    type="file" 
                    id="profilePicture"
                    name="profilePicture"
                    accept="image/*"
                    @change="fileChosen"
                    class="hidden"
                >

                @error('profilePicture')
                    <span class="text-red-500 text-sm text-center">{{ $message }}</span>
                @enderror

                <!-- Buttons -->
                <div class="flex flex-col gap-2">
                    <flux:button 
                        variant="primary"
                        type="submit"
                    >
                        {{ __('Upload') }}
                    </flux:button>

                    <flux:button 
                        @click="profileModal = false"
                        variant="filled"
                        type="cancel"
                    >
                        {{ __('Close') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</div>