<?php

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public Post $post;
}

?>

<div class="flex flex-col bg-white dark:bg-zinc-900 md:rounded-xl md:shadow-sm  md:border md:border-zinc-200 md:dark:border-zinc-800 md:hover:shadow-md transition-shadow">
    <!-- Post Header -->
    <div class="md:px-4 py-3 border-b border-zinc-100 dark:border-zinc-800">
        <div class="flex justify-between items-start">
            <div class="flex items-center gap-3 flex-1">
                <a href="/user/{{ $post->user->id }}" class="flex-shrink-0">
                    <img 
                        src="{{ asset($post->user->profile_photo_path) }}" 
                        alt="{{ $post->user->name }}" 
                        class="w-11 h-11 rounded-full object-cover border-2 border-zinc-200 dark:border-zinc-700 hover:border-blue-400 transition-colors"
                    >
                </a>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="/user/{{ $post->user->id }}" class="font-semibold text-zinc-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            {{ $post->user->name }}
                        </a>
                        @if ($post->user->popular)
                            <img src="{{asset('images/image.png')}}" alt="verified" class="h-4 w-4">
                        @endif
                        <span class="text-xs font-medium px-2 py-1 bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 rounded-full">
                            @if ($post->privacy == 0)
                                <i class="fa-solid fa-earth-americas mr-1"></i>
                            @elseif ($post->privacy == 1)
                                <i class="fa-solid fa-user-group mr-1"></i>
                            @elseif ($post->privacy == 2)
                                <i class="fa-solid fa-lock mr-1"></i>
                            @endif
                        </span>
                    </div>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1" title="{{ $post->created_at->format('M j, Y g:i A') }}">
                        @if ($post->created_at->diffInHours(now()) < 672)
                            {{ $post->created_at->diffForHumans() }}
                        @else
                            {{ $post->created_at->format('M j, Y') }}
                        @endif
                    </p>
                </div>
            </div>
            <livewire:posts.post-options :post="$post" />
        </div>
    </div>

    <!-- Post Content -->
    <div class="md:px-4 py-4">
        <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-2">
            {{ $post->caption }}
        </h3>
        <p class="text-zinc-700 dark:text-zinc-300 text-sm leading-relaxed line-clamp-4">
            {!! nl2br(e($post->description)) !!}
        </p>
    </div>

    <!-- Post Attachments -->
    @if ($post->attachments()->count() >= 1)
        <livewire:attachments.post :post="$post" />
    @endif

    <!-- Engagement Stats & Actions -->
    <div class="md:px-4 py-3">
        <livewire:posts.like :post="$post" />
    </div>

    <!-- Recent Comment Preview -->
    @php
        $recentComment = $post->recentComment();
    @endphp

    @if($recentComment)
        <a href="{{ route('posts.show', $post->id) }}" class="flex mt-4 gap-2 px-4">
            <img src="{{ asset($recentComment->user->profile_photo_path) }}" alt="user-profile" class="w-8 h-8 rounded-full inline mt-1 border">
            <div>
                <div class="w-full rounded-xl bg-zinc-100 dark:bg-zinc-700 px-3 py-1">
                    <p class="font-semibold">
                        <span class="text-zinc-800 dark:text-zinc-100 text-sm">{{ $recentComment->user->name }}</span>
                        <small class="text-xs ml-2 text-zinc-500 dark:text-zinc-400">
                            @if ($recentComment->created_at->diffInHours(now()) < 24)
                                {{ $recentComment->created_at->diffForHumans() }}
                            @else
                                {{ $recentComment->created_at->format('M j, Y g:i A') }}
                            @endif
                        </small>
                    </p>
                    <p class="text-zinc-800 dark:text-zinc-100 text-sm">{{ $recentComment->content }}</p>
                </div>
                <div class="ml-3 mt-2 text-sm flex gap-4">
                    <button class="cursor-pointer flex items-center gap-1 hover:scale-110 transition-all">
                        @if ($recentComment->likes->where('user_id', Auth::id())->count())
                            <i class="fa-solid fa-heart text-lg" style="color: #ff6961"></i>
                        @else
                            <i class="far fa-heart text-lg"></i>
                        @endif
                        {{ $recentComment->likes->count() }}
                    </button>

                    <button onclick="showToast()" class="cursor-pointer flex items-center gap-1 hover:scale-110 transition-all">
                        <p class="text-md mr-1">Reply</p>
                    </button>
                </div>
            </div>
        </a>
    @endif

    <!-- Comment Box -->
    <div class="md:px-4 py-3 md:border-t md:border-zinc-100 md:dark:border-zinc-800 md:bg-zinc-50 md:dark:bg-zinc-800/50">
        <livewire:posts.comment-box :post="$post"/>
    </div>

    <div class="md:hidden -mx-4 border border-zinc-300 dark:border-zinc-600"></div>
</div>