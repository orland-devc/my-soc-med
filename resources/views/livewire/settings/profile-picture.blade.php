<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public Post $post;

    public function like(): void {
        $user = Auth::user();
        if ($this->post->likes()->where('user_id', $user->id)->exists()) {
            $this->post->likes()->where('user_id', $user->id)->delete();
        } else {
            $this->post->likes()->create(['user_id' => $user->id]);
        }
        $this->post->refresh();
    }
};
?>

<div class="flex flex-col items-center gap-4 mt-4" x-data="{profileModal: false}">
    <div class="flex">
        {{-- <a
            @click="profileModal = true"
            class="border-2 w-32 h-32 border-zinc-300 dark:border-zinc-600 active:border-orange-400 cursor-pointer"
        >
            <img 
                src="{{ asset(Auth::user()->profile_photo_path) }}" 
                class="rounded-full w-32 h-32"
                alt="Profile photo"
            >
        </a> --}}

        <a 
            @click="
                profileModal = true;
                const profileSection = document.getElementById('profileSection');
                profileSection.classList.add('overflow-hidden')
            "
            class="border rounded-full w-32 h-32"
        >
            <img 
                src="{{ asset(Auth::user()->profile_photo_path) }}" 
                class="rounded-full"
                alt="Profile photo"
            >   
        </a> 

        {{-- <a @click="profileModal = true" class="w-9 h-9 cursor-pointer">
            <img src="{{ asset(Auth::user()->profile_photo_path) }}" alt="user-profile"
            class="rounded-full inline border">
        </a> --}}
        
    </div>

    <div 
        x-show="profileModal"
        @click.away="profileModal = false"
        x-transition
        class="fixed inset-0 bg-accent">

        <div class="rounded-xl p-6 flex flex-col gap-4">
            <p class="font-bold">Change Photo</p>
            <img 
                src="{{ asset(Auth::user()->profile_photo_path) }}" 
                class="w-40 h-40 rounded-lg object-cover border-2 border-zinc-300 dark:border-zinc-600"
                alt="Profile photo"
            >
        </div>

    </div>

</div>