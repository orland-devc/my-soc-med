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

<div class="flex items-center gap-2">
    <img src="{{ asset(Auth::user()->profile_photo_path) }}" alt="user-profile" class="w-8 h-8 rounded-full border object-cover">
    <div class="flex items-center w-full gap-2 dark:bg-zinc-800 bg-zinc-100 p-1 rounded-2xl">
        <input 
            wire:model.defer="commentText" 
            type="text" 
            placeholder="Write a comment..." 
            class="w-full focus:outline-0 rounded-md px-2 transition-all"
            wire:keydown.enter="addComment"
        >
        <label for="attachments" class="px-3 py-2 text-lg hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-xl cursor-pointer active:bg-zinc-300 dark:active:bg-zinc-600 transition-all">
            <i class="fa-solid fa-paperclip"></i>
        </label>
        <input type="file" id="attachments" name="attachments[]" class="hidden" multiple onchange="previewFiles(this.files)" accept="image/*">
        <flux:button variant="filled" wire:click="addComment" class="rounded-xl text-xl">
            <i class="fa-solid fa-paper-plane transition-all"></i>
        </flux:button>
    </div>
</div>

<script>
    let selectedFiles = [];

    function previewFiles(files) {
        const filePreview = document.getElementById('filePreview');
        filePreview.innerHTML = '';

        selectedFiles = [...selectedFiles, ...files];

        if (selectedFiles.length === 0) {
            return;
        }

        selectedFiles.forEach((file) => {
            const reader = new FileReader();

            reader.addEventListener('load', () => {
                const imgPreview = document.createElement('div');
                imgPreview.classList.add('m-2', 'relative', 'inline-block');

                const img = document.createElement('img');
                img.src = reader.result;
                img.classList.add('h-20', 'w-20', 'object-cover', 'rounded-lg', 'border', 'border-blue-800');

                const removeBtn = document.createElement('button');
                removeBtn.classList.add('transform', 'translate-x-2', '-translate-y-2', 'absolute', 'top-0', 'right-0', 'bg-red-500', 'text-white', 'rounded-full', 'w-6', 'h-6', 'flex', 'items-center', 'justify-center', 'focus:outline-none', 'hover:bg-red-600', 'transition-colors', 'duration-200');
                removeBtn.innerHTML = '&times;';
                removeBtn.addEventListener('click', () => {
                    imgPreview.remove();
                    selectedFiles = selectedFiles.filter(f => f !== file);
                });

                imgPreview.appendChild(img);
                imgPreview.appendChild(removeBtn);
                filePreview.appendChild(imgPreview);
            });

            reader.readAsDataURL(file);
        });
    }

</script>