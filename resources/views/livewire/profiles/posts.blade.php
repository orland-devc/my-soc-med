<?php

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public Post $post;

}

?>

<div class="flex flex-col gap-2">
    <div>
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <img src="{{ asset($post->user->profile_photo_path) }}" alt="user-profile" class="w-9 h-9 rounded-full inline me-2 object-cover">
                <div>
                    <a href="/user/{{ $post->user->id }}" class="flex items-center gap-1 font-semibold hover:underline mb-[-5px]">
                        {{ $post->user->name }}
                        @if ($post->user->popular)
                            <img src="{{asset('images/image.png')}}" alt="" class="h-4">
                        @endif
                    </a>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400"title="{{ $post->created_at->format('M j, Y g:i A') }}">
                        @if ($post->created_at->diffInHours(now()) < 672)
                            {{ $post->created_at->diffForHumans() }}
                        @else
                            {{ $post->created_at->format('M j, Y g:i A') }}
                        @endif

                        @if ($post->privacy == 0)
                            <i class="fa-solid fa-earth-americas"></i>
                        @elseif ($post->privacy == 1)
                            <i class="fa-solid fa-user-group"></i>
                        @elseif ($post->privacy == 2)
                            <i class="fa-solid fa-lock"></i>
                        @endif                    
                    </p>
                </div>
            </div>

            <livewire:posts.post-options :post="$post" />

        </div>
        <p class="py-2 font-semibold text-xl">{{ $post->caption }}</p>
        <p>{!! nl2br(e($post->description)) !!}</p>
    </div>

    @if ($post->attachments()->count() >= 1)
        <livewire:attachments.post :post="$post" />
    @endif

    <livewire:posts.like :post="$post" />   

    @php
        $recentComment = $post->recentComment();
    @endphp

    @if($recentComment)
        <a href="{{ route('posts.show', $post->id) }}" class="flex mt-4 gap-2">
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
    <livewire:posts.comment-box :post="$post"/>
</div>
