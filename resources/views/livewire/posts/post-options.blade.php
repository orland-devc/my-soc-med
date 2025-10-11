<?php

use App\Models\Post;
use App\Models\SavedPost;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public Post $post;
    public bool $isSaved = false;
    public string $editedContent = '';

    public function mount()
    {
        if (Auth::check()) {
            $this->isSaved = SavedPost::where('user_id', Auth::id())
                ->where('post_id', $this->post->id)
                ->exists();
        }

        $this->editedContent = $this->post->content ?? '';
    }

    public function deletePost(): void
    {
        if ($this->post->uploader != Auth::user()->id) return;

        $this->post->delete();
        $this->dispatch('post-deleted');
        $this->redirect('/posts', navigate: true);
    }

    public function updatePost(): void
    {
        if ($this->post->uploader != Auth::user()->id) return;

        $this->validate([
            'editedCaption' => 'sometimes|string|max:2000',
            'editedContent' => 'sometimes|string|max:2000',
        ]);

        $this->post->update([
            'caption' => $this->editedCaption,
            'content' => $this->editedContent,
        ]);
        $this->dispatch('post-updated');
        $this->redirect("/post/{$this->post->id}", navigate: true);
    }

    public function reportPost(): void
    {
        session()->flash('message', 'Post has been reported.');
    }

    public function savePost(): void
    {
        $user = Auth::user();
        if (! $user) return;

        $saved = SavedPost::where('user_id', $user->id)
            ->where('post_id', $this->post->id)
            ->first();

        if ($saved) {
            $saved->delete();
            $this->isSaved = false;
        } else {
            SavedPost::create([
                'user_id' => $user->id,
                'post_id' => $this->post->id,
            ]);
            $this->isSaved = true;
        }
    }
};
?>

<div 
    x-data="{ open: false, showDeleteModal: false, showEditModal: false }" 
    class="relative"
>
    {{-- Three Dots --}}
    <button 
        @click="open = !open"
        class="cursor-pointer flex items-center p-2 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-full transition-all"
    >
        <i class="fa-solid fa-ellipsis text-xl"></i>
    </button>

    {{-- Dropdown --}}
    <div 
        x-show="open"
        @click.away="open = false"
        x-transition
        class="absolute right-0 mt-2 w-44 bg-white dark:bg-zinc-800 rounded-xl shadow-lg overflow-hidden z-50"
    >
        @if (Auth::user()->id == $post->uploader)
            <button 
                @click="showDeleteModal = true; open = false"
                class="w-full text-left px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-red-500"
            >
                <i class="fa-solid fa-trash"></i>
                Delete
            </button>

            <button 
                @click="showEditModal = true; open = false"
                class="w-full text-left px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700"
            >
                <i class="fa-solid fa-pen-to-square"></i>
                Edit
            </button>
        @else
            <button 
                wire:click="reportPost"
                class="w-full text-left px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700"
            >
                <i class="fa-solid fa-flag"></i>
                Report
            </button>
            <button 
                wire:click="savePost"
                class="w-full text-left px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700"
            >
                @if ($isSaved)
                    <i class="fa-solid fa-bookmark text-red-400"></i> Unsave
                @else
                    <i class="fa-solid fa-bookmark"></i> Save
                @endif
            </button>
        @endif

        <button 
            @click="navigator.clipboard.writeText('{{ url('/post/'.$post->id) }}'); open = false;"
            class="w-full text-left px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700"
        >
            <i class="fa-solid fa-link"></i>
            Copy link
        </button>
    </div>

    {{-- DELETE MODAL --}}
    <div 
        x-show="showDeleteModal"
        x-transition
        class="fixed inset-0 bg-black flex justify-center items-center z-50"
    >
        <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 w-80">
            <h2 class="text-lg font-semibold mb-2">Delete Post?</h2>
            <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4">
                Are you sure you want to delete this post? This action cannot be undone.
            </p>
            <div class="flex justify-end gap-3">
                <button 
                    @click="showDeleteModal = false"
                    class="px-3 py-1 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-700"
                >Cancel</button>
                <button 
                    wire:click="deletePost"
                    @click="showDeleteModal = false"
                    class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded-lg"
                >Delete</button>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div 
        x-show="showEditModal"
        x-transition
        class="modal-bg fixed inset-0 bg-black flex justify-center items-center z-50"
    >
        <div class="bg-white sm:w-full md:w-2/3 lg:w-160 dark:bg-zinc-800 rounded-xl p-6 w-96">
            <h2 class="text-lg font-semibold mb-3">Edit Post</h2>
            <input 
                wire:modal.defer="editedCaption"
                type="text"
                class="w-full rounded-lg p-2 mb-3 bg-zinc-100 dark:bg-zinc-700 focus:outline-none"
                >
            <textarea 
                wire:model.defer="editedContent"
                class="w-full h-28 rounded-lg p-2 bg-zinc-100 dark:bg-zinc-700 focus:outline-none resize-none"
            ></textarea>
            <div class="flex justify-end gap-3 mt-4">
                <flux:button @click="showEditModal = false">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button variant="primary" wire:click="updatePost" @click="showEditModal = false">
                    {{ __('Save') }}
                </flux:button>
            </div>
        </div>
    </div>
</div>
