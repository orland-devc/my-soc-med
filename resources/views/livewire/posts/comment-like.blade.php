<?php

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public Comment $comment;

    public function like(): void {
        $user = Auth::user();
        if ($this->comment->likes()->where('user_id', $user->id)->exists()) {
            $this->comment->likes()->where('user_id', $user->id)->delete();
        } else {
            $this->comment->likes()->create(['user_id' => $user->id]);
        }
        $this->comment->refresh();
    }
};
?>

<div wire:poll.1s>
    <button wire:click="like" class="cursor-pointer flex items-center gap-1 hover:scale-110 transition-all">
        @if ($comment->likes->where('user_id', Auth::id())->count())
            <p class="text-md mr-1 font-bold" style="color: #ff6961">Like</p>
        @else
            <p class="text-md mr-1">Like</p>
        @endif
        {{ $comment->likes->count() }}
    </button>
</div>