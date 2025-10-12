<?php

use App\Models\Post;
use App\Models\Comment;
use App\Models\CommentReply;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public Post $post;
    public array $replyText = []; 

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

    public function reply_like(int $replyId): void {
        $reply = CommentReply::findOrFail($replyId);
        $user = Auth::user();

        if ($reply->likes()->where('user_id', $user->id)->exists()) {
            $reply->likes()->where('user_id', $user->id)->delete();
        } else {
            $reply->likes()->create(['user_id' => $user->id]);
        }

        $reply->refresh();
        $this->post->load('comments.replies.likes');
    }

    public function replyComment(int $commentId): void
    {
        $text = trim($this->replyText[$commentId] ?? '');

        if ($text === '') return;

        CommentReply::create([
            'comment_id' => $commentId,
            'user_id' => Auth::id(),
            'content' => $text,
        ]);

        $this->replyText[$commentId] = '';

        $this->post->load('comments.replies');
        $this->dispatch('comment-added');
    }
};
?>

<div wire:poll.3s>
    @foreach ($post->comments->sortByDesc('created_at') as $comment)
        <div class="flex mt-2 gap-3" x-data="{ showReply: false, showCommentReplies: false }">
            <img src="{{ asset('images/user-profile.jpg') }}" alt="user-profile" class="w-9 h-9 rounded-full inline">
            <div class="w-full">
                <div class="flex justify-between gap-3">
                    <div class="">
                        <p class="flex items-center gap-1 min-w-0">
                            <a href="/posts/{{ $comment->user_id }}"
                               class="text-zinc-500 dark:text-zinc-400 hover:underline truncate max-w-[14rem] sm:max-w-[16rem] md:max-w-[18rem] lg:max-w-[20rem]">
                                {{ $comment->user->name }}
                            </a>
                            @if ($comment->user_id == $comment->post->uploader)
                                <span class="bg-zinc-300 dark:bg-zinc-600 text-zinc-600 dark:text-zinc-200 text-sm px-1 rounded-md flex-shrink-0">Creator</span>
                            @endif
                        </p>
                        <p class="" title="{{ $comment->created_at->format('M j, Y g:i A') }}">
                            {{ $comment->content }}
                        </p>
                    </div>

                    {{-- Like Button --}}
                    <button wire:click="like({{ $comment->id }})"
                            class="cursor-pointer pt-2 flex flex-col items-center hover:scale-110 transition-all">
                        @if ($comment->likes->where('user_id', Auth::id())->count())
                            <i class="fa-solid fa-heart text-xl" style="color: #ff6961"></i>
                        @else
                            <i class="far fa-heart text-xl text-zinc-500"></i>
                        @endif
                        {{ $comment->likes->count() }}
                    </button>
                </div>

                {{-- Reply + timestamp --}}
                <div class="mb-2 text-sm flex gap-3">
                    <span class="text-zinc-500 dark:text-zinc-400">
                        @if ($comment->created_at->diffInHours(now()) < 168)
                            {{ $comment->created_at->diffForHumans() }}
                        @else
                            {{ $comment->created_at->format('M j, Y g:i A') }}
                        @endif
                    </span>

                    <button @click="showReply = !showReply" class="text-zinc-500 dark:text-zinc-400 cursor-pointer flex items-center gap-1 hover:scale-110 transition-all">
                        <p class="font-semibold">Reply</p>
                    </button>                    
                </div>

                {{-- View Replies Button --}}
                @if ($comment->replies->count())
                    <div class="mb-2 text-sm flex gap-3">
                        <button
                            @click="showCommentReplies = !showCommentReplies" 
                            class="text-zinc-500 dark:text-zinc-400 cursor-pointer flex items-center gap-1 transition-all">
                            <p class="font-semibold">
                                <span x-text="showCommentReplies ? 'Hide replies' : 'View replies'"></span>
                                ({{ $comment->replies->count() }})
                            </p>
                            <i :class="showCommentReplies ? 'fa-chevron-up' : 'fa-chevron-down'" class="fa-solid text-sm"></i>
                        </button>
                    </div>
                @endif

                {{-- Display Replies (retractable) --}}
                {{-- <div x-show="showCommentReplies" x-transition class="mt-2 max-h-80 overflow-y-auto border-l-2 border-zinc-300 dark:border-zinc-600 pl-4"> --}}
                <div x-show="showCommentReplies" x-transition class="mt-2 border-l-2 border-zinc-300 dark:border-zinc-600 pl-4">
                    @foreach ($comment->replies as $reply)
                        <div class="flex w-full justify-between gap-2 mt-3">
                            <div class="flex gap-3">
                                <img src="{{ asset('images/user-profile.jpg') }}" alt="user-profile"
                                    class="w-9 h-9 rounded-full inline">
                                <div class="">
                                    <div class="flex gap-1 items-center">
                                        <a href="/posts/{{ $reply->user_id }}" class="text-zinc-500 dark:text-zinc-400 hover:underline truncate max-w-[10rem] sm:max-w-[12rem] md:max-w-[14rem] lg:max-w-full">
                                            {{ $reply->user->name }}
                                        </a>
                                        @if ($reply->user_id == $reply->comment->post->uploader)
                                            <span class="bg-zinc-300 dark:bg-zinc-600 text-zinc-600 dark:text-zinc-200 text-sm px-1 rounded-md flex-shrink-0">Creator</span>
                                        @endif
                                    </div>

                                    <p>{{ $reply->content }}</p>
                                    
                                    <p class="text-zinc-500 dark:text-zinc-400 text-sm" title="{{ $reply->created_at->format('M j, Y g:i A') }}">
                                        @if ($reply->created_at->diffInHours(now()) < 168)
                                            {{ $reply->created_at->diffForHumans() }}
                                        @else
                                            {{ $reply->created_at->format('M j, Y g:i A') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <button wire:click="reply_like({{ $reply->id }})"
                                    class="cursor-pointer pt-2 flex flex-col items-center hover:scale-110 transition-all">
                                @if ($reply->likes->where('user_id', Auth::id())->count())
                                    <i class="fa-solid fa-heart text-xl" style="color: #ff6961"></i>
                                @else
                                    <i class="far fa-heart text-xl text-zinc-500"></i>
                                @endif
                                {{ $reply->likes->count() }}
                            </button>
                        </div>
                    @endforeach
                </div>

                {{-- Reply input --}}
                <div x-show="showReply"
                     class="mt-3 flex items-center gap-2 dark:bg-zinc-800 bg-zinc-50 p-1 rounded-2xl">
                    <input
                        wire:model.defer="replyText.{{ $comment->id }}"
                        type="text"
                        placeholder="Reply to this comment..."
                        class="w-full focus:outline-0 rounded-md px-2 transition-all"
                        wire:keydown.enter="replyComment({{ $comment->id }})"
                    >
                    <flux:button variant="filled" wire:click="replyComment({{ $comment->id }})">
                        <i class="fa-solid fa-paper-plane"></i>
                    </flux:button>
                </div>
            </div>
        </div>
    @endforeach
</div>
