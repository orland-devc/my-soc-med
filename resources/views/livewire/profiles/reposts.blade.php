<?php

use App\Models\Repost;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public Repost $repost;

}

?>

<div class="flex flex-col gap-2">
    <div>
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <img src="{{ asset($repost->post->user->profile_photo_path) }}" alt="user-profile" class="w-9 h-9 rounded-full inline me-2 object-cover">
                <div>
                    <a href="/user/{{ $repost->post->user->id }}" class="flex items-center gap-1 font-semibold hover:underline mb-[-5px]">
                        {{ $repost->post->user->name }}
                        @if ($repost->post->user->popular)
                            <img src="{{asset('images/image.png')}}" alt="" class="h-4">
                        @endif
                    </a>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400"title="{{ $repost->post->created_at->format('M j, Y g:i A') }}">
                        @if ($repost->post->created_at->diffInHours(now()) < 672)
                            {{ $repost->post->created_at->diffForHumans() }}
                        @else
                            {{ $repost->post->created_at->format('M j, Y g:i A') }}
                        @endif
                    </p>
                </div>
            </div>

            <livewire:posts.post-options :post="$repost" />

        </div>
        <p class="py-2 font-semibold text-xl">{{ $repost->post->caption }}</p>
        <p>{!! nl2br(e($repost->post->description)) !!}</p>
    </div>

    {{-- @foreach ($repost->post->attachments as $attachment)
        <div class="flex mx-[-15px]">
            <img src="{{ asset($attachment->file_location) }}" alt="{{ $attachment->file_name }}" class="max-h-[40rem] lg:max-h-[54rem] min-w-full object-cover">
        </div>
    @endforeach --}}

    @if ($repost->post->attachments()->count() >= 1)
        <livewire:attachments.post :post="$post" />
    @endif

    <livewire:posts.like :post="$repost" />   
    <livewire:posts.comment-box :post="$repost"/>
</div>
