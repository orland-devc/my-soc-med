<?php

use App\Models\Post;
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

<div wire:poll.1s class="flex gap-4 mt-3 text-zinc-700 dark:text-zinc-300">
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
    <button onclick="showToast()" class="cursor-pointer flex items-center gap-1 hover:scale-110 transition-all">
        <i class="fa-solid fa-share text-lg"></i>
        <span>{{ $post->reposts->count() }}</span>
    </button>
</div>
