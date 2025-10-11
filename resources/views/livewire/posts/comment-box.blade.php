<?php

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public Post $post;
    public string $commentText = '';

    public function addComment(): void
    {
        if (trim($this->commentText) === '') return;

        Comment::create([
            'post_id' => $this->post->id,
            'user_id' => Auth::id(),
            'content' => $this->commentText,
        ]);

        $this->commentText = '';
        $this->post->refresh();

        $this->dispatch('comment-added');
    }
};
?>

<div class="mt-3 flex items-center gap-2 dark:bg-zinc-700 bg-zinc-100 p-1 rounded-2xl">
    <input 
        wire:model.defer="commentText" 
        type="text" 
        placeholder="Write a comment..." 
        class="w-full focus:outline-0 rounded-md px-2 transition-all"
        wire:keydown.enter="addComment"
    >
    <button wire:click="addComment" class="text-lg justify-center flex items-center p-2 text-zinc-800 bg-zinc-200  hover:bg-zinc-300 dark:text-white dark:bg-zinc-800  dark:hover:bg-zinc-600 cursor-pointer rounded-xl transition-all">
        <i class="fa-solid fa-paper-plane"></i>
    </button>
</div>
