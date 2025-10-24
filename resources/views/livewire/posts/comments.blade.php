<?php

use App\Models\Post;
use App\Models\Comment;
use App\Models\CommentReply;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public Post $post;
    public array $replyText = []; 

    public function mount(Post $post)
    {
        $this->post = $post->load(['comments.user', 'comments.likes', 'comments.replies.user', 'comments.replies.likes']);
        $this->refreshComments();
    }

    public function refreshComments(): void
    {
        $creatorComments = $this->post->comments()
            ->where('user_id', $this->post->user_id)
            ->with(['user', 'likes', 'replies.user', 'replies.likes'])
            ->orderBy('created_at', 'asc') // oldest first
            ->get();

        $otherComments = $this->post->comments()
            ->where('user_id', '!=', $this->post->user_id)
            ->with(['user', 'likes', 'replies.user', 'replies.likes'])
            ->orderBy('created_at', 'desc') // newest first
            ->get();

        $this->post->setRelation('comments', $creatorComments->concat($otherComments));
    }

    public function like(int $commentId): void {
        $comment = Comment::findOrFail($commentId);
        $user = Auth::user();

        if ($comment->likes()->where('user_id', $user->id)->exists()) {
            $comment->likes()->where('user_id', $user->id)->delete();
        } else {
            $comment->likes()->create(['user_id' => $user->id]);
        }

        // $comment->refresh();
        // $this->post->load('comments.likes');
        $this->refreshComments();
    }

    public function reply_like(int $replyId): void {
        $reply = CommentReply::findOrFail($replyId);
        $user = Auth::user();

        if ($reply->likes()->where('user_id', $user->id)->exists()) {
            $reply->likes()->where('user_id', $user->id)->delete();
        } else {
            $reply->likes()->create(['user_id' => $user->id]);
        }

        $this->refreshComments();
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

        $this->refreshComments();
        $this->dispatch('comment-added');
    }
};
?>

<div wire:poll.3s="refreshComments">
    @foreach ($post->comments as $comment)
        <div class="flex mt-2 gap-3" x-data="{ showReply: false, showCommentReplies: false }">
            <a href="/user/{{$comment->user->id}}" class="">
                <img src="{{ asset($comment->user->profile_photo_path) }}" alt="user-profile"
                class="rounded-full inline border w-9 h-9 object-cover">
            </a>
            <div class="w-full">
                <div
                    class="select-none"
                    x-data="{ showMenu: false, menuX: 0, menuY: 0 }"
                    @contextmenu.prevent="
                        showMenu = true;
                        menuX = $event.pageX;
                        menuY = $event.pageY;
                    ">
                    <div class="flex justify-between gap-3 relative">
                        <div>
                            <p class="flex items-center gap-1 min-w-0">
                                <a href="/user/{{ $comment->user_id }}"
                                    class="text-zinc-500 dark:text-zinc-400 hover:underline truncate max-w-[14rem] sm:max-w-[16rem] md:max-w-[18rem] lg:max-w-[20rem]">
                                    {{ $comment->user->name }}
                                </a>
                                @if ($comment->user->popular)
                                    <img src="{{asset('images/image.png')}}" alt="" class="h-4">
                                @endif
                                @if ($comment->user_id == $comment->post->user_id)
                                    <span class="bg-zinc-300 dark:bg-zinc-600 text-zinc-600 dark:text-zinc-200 text-sm px-1 rounded-md flex-shrink-0">
                                        Creator
                                    </span>
                                @endif
                            </p>
                            <p title="{{ $comment->created_at->format('M j, Y g:i A') }}" class="">
                                {{ $comment->content }}
                            </p>
                            @if ($comment->likedByCreator())
                                <div class="flex items-center relative w-6">
                                    <i class="fas fa-heart" style="color: #ff6961"></i>
                                    <img src="{{ asset($comment->post->user->profile_photo_path) }}" alt="" class="h-3 w-3 border border-white dark:border-zinc-900 object-cover rounded-full absolute bottom-0 right-0">
                                </div>
                            @endif
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

                        {{-- Context Menu --}}
                        <div 
                            x-show="showMenu" 
                            x-transition 
                            class="absolute right-5 bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 shadow-lg rounded-md text-sm w-32 z-50"
                            @click.away="showMenu = false">
                            @if ($post->user_id == Auth::id())
                                <button class="w-full flex justify-between items-center px-3 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700 active:bg-zinc-200 dark:active:bg-zinc-800">
                                    Pin 
                                    <i class="fa fa-thumbtack text-zinc-500 dark:text-zinc-500"></i>
                                </button>                            
                            @endif

                            <button
                                @click="
                                    const contentCopy = @js($comment->content);
                                    if (navigator.clipboard && window.isSecureContext) {
                                        navigator.clipboard.writeText(contentCopy);
                                    } else {
                                        const textarea = document.createElement('textarea');
                                        textarea.value = contentCopy;
                                        textarea.style.position = 'fixed';
                                        textarea.style.left = '-9999px';
                                        document.body.appendChild(textarea);
                                        textarea.focus();
                                        textarea.select();
                                        document.execCommand('copy');
                                        document.body.removeChild(textarea);
                                    }
                                    showMenu = false;
                                "
                                class="w-full flex justify-between items-center px-3 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700 active:bg-zinc-200 dark:active:bg-zinc-800"
                            >
                                Copy
                                <i class="fas fa-copy text-zinc-500 dark:text-zinc-500"></i>
                            </button>

                            @if ($comment->user_id == Auth::id())
                                <button class="w-full flex justify-between items-center px-3 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700 active:bg-zinc-200 dark:active:bg-zinc-800">
                                    Edit
                                    <i class="fa fa-pen text-zinc-500 dark:text-zinc-500"></i>
                                </button>                            
                            @endif

                            @if ($comment->user_id == Auth::id() || $post->user_id == Auth::id())
                                <button class="w-full flex justify-between items-center px-3 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700 active:bg-zinc-200 dark:active:bg-zinc-800 text-red-500">
                                    Delete
                                    <i class="fa fa-trash"></i>
                                </button>                              
                            @endif

                        </div>
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
                </div>

                {{-- Display Replies (retractable) --}}
                {{-- <div x-show="showCommentReplies" x-transition class="mt-2 max-h-80 overflow-y-auto border-l-2 border-zinc-300 dark:border-zinc-600 pl-4"> --}}
                <div x-show="showCommentReplies" x-transition class="mt-2 border-l-2 border-zinc-300 dark:border-zinc-600 pl-4">
                    @foreach ($comment->replies as $reply)
                        <div class="flex w-full justify-between gap-2 mt-3">
                            <div class="flex gap-3">
                                <a href="/user/{{$reply->user->id}}" class="">
                                    <img src="{{ asset($reply->user->profile_photo_path) }}" alt="user-profile"
                                    class="rounded-full inline border w-9 h-9 object-cover">
                                </a>
                                <div class="">
                                    <div class="flex gap-1 items-center">
                                        <a href="/posts/{{ $reply->user_id }}" class="text-zinc-500 dark:text-zinc-400 hover:underline truncate max-w-[10rem] sm:max-w-[12rem] md:max-w-[14rem] lg:max-w-full">
                                            {{ $reply->user->name }}
                                        </a>
                                        @if ($reply->user_id == $reply->comment->post->user_id)
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
