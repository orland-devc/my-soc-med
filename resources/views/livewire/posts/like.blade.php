<?php

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public Post $post;
    public string $captionText = '';

    public function like(): void {
        $user = Auth::user();
        if ($this->post->likes()->where('user_id', $user->id)->exists()) {
            $this->post->likes()->where('user_id', $user->id)->delete();
        } else {
            $this->post->likes()->create(['user_id' => $user->id]);
        }
        $this->post->refresh();
    }

    public function deleteRepost():void
    {
        $user = Auth::user();
        $this->post->reposts()->where('user_id', $user->id)->delete();
    }

    public function repost(): void
    {
        if (trim($this->captionText) === '') return;
        $user = Auth::user();
        $this->post->reposts()->create([
            'user_id' => $user->id,
            'post_id' => $this->post->id,
            'caption' => $this->captionText,
        ]);

        $this->captionText = '';
        $this->post->refresh();
    }

    public function updateRepost(): void
    {
        if (trim($this->captionText) === '') return;
        $user = Auth::user();
        $this->post->reposts()->update([
            'caption' => $this->captionText,
        ]);

        $this->captionText = '';
        $this->post->refresh();
    }

};
?>

<div wire:poll.1s class="flex gap-4 text-zinc-700 dark:text-zinc-300" x-data="{ showRepostModel : false, updateRepostModel : false }">
    {{-- LIKE BUTTON --}}
    <button wire:click="like" class="cursor-pointer flex items-center gap-1 hover:scale-110 transition-all">
        @if ($post->likes->where('user_id', Auth::id())->count())
            <i class="fa-solid fa-heart text-lg" style="color: #ff6961"></i>
        @else
            <i class="far fa-heart text-lg"></i>
        @endif
        {{ $post->likes->count() }}
    </button>

    {{-- COMMENT BUTTON --}}
    @php
        $onPostPage = request()->is('posts/' . $post->id);
    @endphp

    @if ($onPostPage)
        <button class="cursor-pointer flex items-center gap-1 hover:scale-110 transition-all">
            <i class="far fa-comment text-lg"></i>
            {{ $post->comments->count() + $post->comments->sum(fn($comment) => $comment->replies->count()) }}
        </button>

    @else
        <a href="{{ url('/posts/' . $post->id) }}" class="cursor-pointer flex items-center gap-1 hover:scale-110 transition-all">
            <i class="far fa-comment text-lg"></i>
            {{ $post->comments->count() + $post->comments->sum(fn($comment) => $comment->replies->count()) }}
        </a>
    @endif

    {{-- SHARE BUTTON --}}
    @if ($post->reposts->where('user_id', Auth::id())->count())
        <button @click="updateRepostModel = true" class="cursor-pointer flex items-center gap-1 hover:scale-110 transition-all">
            <i class="fas fa-retweet text-lg text-blue-500"></i>
            <span>{{ $post->reposts->count() }}</span>
        </button>
    @else 
        <button @click="showRepostModel = true" class="cursor-pointer flex items-center gap-1 hover:scale-110 transition-all">
            <i class="fas fa-retweet text-lg"></i>
            <span>{{ $post->reposts->count() }}</span>
        </button>
    @endif

    <div 
        x-show="showRepostModel"
        x-transition
        class="modal-bg fixed inset-0 bg-black/50 flex justify-center items-center z-50"
    >
        <div @click.away="showRepostModel = false" class="bg-white sm:w-full md:w-2/3 lg:w-160 dark:bg-zinc-800 rounded-xl p-6 w-96">
            <h2 class="text-lg font-semibold mb-3">Edit Post</h2>
            <input 
                wire:model.defer="captionText"
                type="text"
                class="w-full rounded-lg p-2 mb-3 bg-zinc-100 dark:bg-zinc-700 focus:outline-none"
            >
            
            <div class="text-zinc-600 dark:text-zinc-400">
                Repost {{$post->user->name}}'s flex
            </div>

            <div class="flex justify-end gap-3 mt-4">
                <flux:button @click="showRepostModel = false">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button variant="primary" wire:click="repost" @click="showRepostModel = false">
                    {{ __('Save') }}
                </flux:button>
            </div>
        </div>
    </div>

        <div 
        x-show="updateRepostModel"
        x-transition
        class="modal-bg fixed inset-0 bg-black/50 flex justify-center items-center z-50"
    >
        <div @click.away="updateRepostModel = false" class="bg-white sm:w-full md:w-2/3 lg:w-160 dark:bg-zinc-800 rounded-xl p-6 w-96">
            <h2 class="text-lg font-semibold mb-3">Edit Post</h2>
            <input 
                wire:model.defer="captionText"
                type="text"
                placeholder="Write something..."
                value="" {{-- preview caption here --}}
                class="w-full rounded-lg p-2 mb-3 bg-zinc-100 dark:bg-zinc-700 focus:outline-none"
            >
            
            <div class="text-zinc-600 dark:text-zinc-400">
                Repost {{$post->user->name}}'s flex
            </div>

            <div class="flex justify-end gap-3 mt-4">
                <button 
                    wire:click="deleteRepost"
                    @click="updateRepostModel = false"
                    class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded-lg"
                >Delete</button>
                <flux:button variant="primary" wire:click="updateRepost" @click="updateRepostModel = false">
                    {{ __('Update') }}
                </flux:button>
            </div>
        </div>
    </div>


</div>
