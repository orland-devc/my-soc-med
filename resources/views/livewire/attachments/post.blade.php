<?php

use App\Models\Post;
use Livewire\Volt\Component;

new class extends Component {
    public Post $post;
};
?>

@php
    $attachments = $post->attachments;
    $visibleAttachments = $attachments->take(4);
    $remainingAttachments = $attachments->skip(4);
    $remainingCount = $attachments->count() - 4;
@endphp

@if ($post->attachments()->count() >= 1)
    @if ($attachments->count() > 1)
    @if ($attachments->count() == 3)
        <div class="grid grid-cols-3 gap-2">
    @else
        <div class="grid grid-cols-2 gap-2">
    @endif
        @foreach ($visibleAttachments as $index => $attachment)
            @php
                $fileUrl = asset($attachment->file_location);
            @endphp

            <a 
                href="{{ $fileUrl }}" 
                data-lightbox="post-{{ $post->id }}" 
                data-title="{{ $attachment->file_name }}" 
                class="relative overflow-hidden rounded-lg"
            >
                <img 
                    src="{{ $fileUrl }}" 
                    alt="{{ $attachment->file_name }}" 
                    class="w-full h-64 object-cover hover:scale-105 transition-transform duration-300"
                >

                @if ($loop->iteration === 4 && $remainingCount > 0)
                    <div class="absolute inset-0 bg-black/60 flex items-center justify-center text-white text-3xl font-semibold">
                        +{{ $remainingCount }}
                    </div>
                @endif
            </a>
        @endforeach

        @foreach ($remainingAttachments as $hidden)
            <a 
                href="{{ asset($hidden->file_location) }}" 
                data-lightbox="post-{{ $post->id }}" 
                data-title="{{ $hidden->file_name }}" 
                class="hidden"
            ></a>
        @endforeach
    </div>
    @else
    <div class="flex mx-[-15px] justify-center">
        @php
            $file = $attachments->first();
        @endphp
        <a 
            href="{{ asset($file->file_location) }}" 
            data-lightbox="post-{{ $post->id }}" 
            data-title="{{ $file->file_name }}"
        >
            <img 
                src="{{ asset($file->file_location) }}" 
                alt="{{ $file->file_name }}" 
                class="max-h-[40rem] min-w-full object-cover rounded-lg"
            >
        </a>
    </div>
    @endif
@endif