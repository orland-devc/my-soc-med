<?php

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public Post $post;

    public function like(int $commentId): void {
        $comment = Comment::findOrFail($commentId);
        $user = Auth::user();

        if ($comment->likes()->where('user_id', $user->id)->exists()) {
            $comment->likes()->where('user_id', $user->id)->delete();
        } else {
            $comment->likes()->create(['user_id' => $user->id]);
        }

        $comment->refresh();
        $this->post->load('comments.likes');
    }
};
?>

<div wire:poll.3s>
    @foreach ($post->comments->sortByDesc('created_at') as $comment)
        <div class="flex mt-2 gap-2">
            <img src="{{ asset('images/user-profile.jpg') }}" alt="user-profile" class="w-8 h-8 rounded-full inline mt-1">
            <div>
                <div class="w-full rounded-xl bg-zinc-100 dark:bg-zinc-700 px-3 py-1" title="{{ $comment->created_at->format('M j, Y g:i A') }}">
                    <p class="font-semibold">
                        <span class="text-zinc-800 dark:text-zinc-100 text-sm">
                            {{ $comment->user->name }}
                        </span>
                        <span class="text-zinc-500 dark:text-zinc-400 text-xs">
                            @if ($comment->user_id == $comment->post->uploader)
                                (Author)                                
                            @endif
                        </span>
                    </p>
                    <p class="text-zinc-800 dark:text-zinc-100 text-sm">{{ $comment->content }}</p>
                </div>
                <div class="ml-3 text-sm flex gap-4">
                    <button wire:click="like({{ $comment->id }})" class="cursor-pointer flex items-center gap-1 hover:scale-110 transition-all">
                        @if ($comment->likes->where('user_id', Auth::id())->count())
                            <i class="fa-solid fa-heart text-lg" style="color: #ff6961"></i>
                        @else
                            <i class="far fa-heart text-lg"></i>
                        @endif
                        {{ $comment->likes->count() }}
                    </button>

                    <button onclick="showToast()" class="cursor-pointer flex items-center gap-1 hover:scale-110 transition-all">
                        <p class="text-md">Reply</p>
                    </button>

                    <span class="text-zinc-500 dark:text-zinc-400">
                        @if ($comment->created_at->diffInHours(now()) < 168)
                            {{ $comment->created_at->diffForHumans() }}
                        @else
                            {{ $comment->created_at->format('M j, Y g:i A') }}
                        @endif
                    </span>
                </div>
            </div>
        </div>
    @endforeach
</div>
