<?php

use App\Models\Post;
use App\Models\User;
use App\Models\SavedPost;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public Post $post;
    public User $user;
    public bool $isSaved = false;

    public $editedCaption = '';
    public $editedContent = '';
    public $editedPrivacy;
    public bool $isFollowing = false;
    public bool $isFollowBack = false;


    public function mount()
    {
        $auth = Auth::user();
        $this->user = $this->post->user;
        $this->isFollowing = $auth->isFollowing($this->post->user);
        $this->isFollowBack = $this->post->user->isFollowing($auth);

        if (Auth::check()) {
            $this->isSaved = SavedPost::where('user_id', Auth::id())
                ->where('post_id', $this->post->id)
                ->exists();
        }

        $this->editedCaption = $this->post->caption ?? '';
        $this->editedContent = $this->post->description ?? '';
        $this->editedPrivacy = $this->post->privacy ?? 0;
    }

    public function deletePost(): void
    {
        if ($this->post->user_id != Auth::user()->id) return;

        $this->post->delete();
        $this->dispatch('post-deleted');
        $this->redirect('/posts', navigate: true);
    }

    public function updatePost(): void
    {
        if ($this->post->user_id != Auth::user()->id) return;

        $this->validate([
            'editedCaption' => 'nullable|string|max:2000',
            'editedContent' => 'nullable|string|max:2000',
        ]);

        $this->post->update([
            'caption' => $this->editedCaption,
            'description' => $this->editedContent,
        ]);

        $this->dispatch('post-updated');
        $this->redirect("/posts/{$this->post->id}", navigate: true);
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

    public function archivePost(): void
    {
        $user = Auth::user();
        if (! $user) return;

        $this->post->update([
            'archived' => 1,
        ]);
    }

    public function updatePrivacy(): void
    {
        if ($this->post->user_id != Auth::user()->id) return;

        $this->validate([
            'editedPrivacy' => 'nullable|integer|in:0,1,2',
        ]);

        $this->post->update([
            'privacy' => $this->editedPrivacy,
        ]);

        $this->dispatch('post-updated');
        $this->redirect("/posts/{$this->post->id}", navigate: true);
    }

    public function pinPost(): void
    {
        $auth = Auth::user();
        if ($this->post->user_id != $auth->id) return;

        // Unpin all posts of user
        $auth->posts()->update([
            'is_pinned' => false,
        ]);

        // Pin this one
        $this->post->update([
            'is_pinned' => true,
        ]);
    }

    public function toggleFollow(): void
    {
        $auth = Auth::user();
        if (! $auth) return;

        if ($auth->isFollowing($this->user)) {
            $auth->following()->detach($this->user->id);
            $this->isFollowing = false;
        } else {
            $auth->following()->attach($this->user->id);
            $this->isFollowing = true;
        }

        $this->isFollowBack = $this->user->isFollowing($auth);
    }
};
?>

<div 
    x-data="{ open: false, showDeleteModal: false, showEditModal: false, copyToast: false, editPrivacyModal: false }" 
    class="relative"
>
    {{-- Backdrop (blocks clicks but allows closing dropdown) --}}
    <div 
        x-show="open"
        @click="open = false"
        x-transition.opacity
        class="fixed inset-0 z-40 bg-transparent cursor-default"
    ></div>

    {{-- Three Dots --}}
    <button 
        @click="open = !open"
        id="dotOptions"
        class="cursor-pointer flex items-center p-2 hover:bg-zinc-200 dark:hover:bg-zinc-800 active:bg-zinc-200 dark:active:bg-zinc-800 rounded-full transition-all z-30 relative"
    >
        <i class="fa-solid fa-ellipsis text-xl"></i>
    </button>

    {{-- Dropdown --}}
    <div 
        x-show="open"
        x-transition
        class="absolute right-0 mt-2 border w-56 text-sm bg-white dark:bg-zinc-800 rounded-xl shadow-2xl overflow-hidden z-100"
    >
        @if (Auth::user()->id == $post->user_id)
            <button 
                @click="open = false"
                class="w-full flex justify-between items-center px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700 active:bg-zinc-200 dark:active:bg-zinc-600"
            >
                Pin post
                <i class="fa-solid fa-thumbtack text-lg ml-2"></i>
            </button>

            <button 
                wire:click="archivePost"
                @click="open = false"
                class="w-full flex justify-between items-center px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700 active:bg-zinc-200 dark:active:bg-zinc-600"
            >
                Move to archive
                <i class="fa-solid fa-box-archive text-lg ml-2"></i>
            </button>

            <button 
                @click="open = false"
                class="w-full flex justify-between items-center px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700 active:bg-zinc-200 dark:active:bg-zinc-600"
            >
                Disable Comments
                <i class="fa-solid fa-comment-slash text-lg ml-2"></i>
            </button>

            <button 
                @click="showEditModal = true; open = false"
                class="w-full flex justify-between items-center px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700 active:bg-zinc-200 dark:active:bg-zinc-600"
            >
                Edit post
                <i class="fa-solid fa-pen text-lg ml-2"></i>
            </button>

            <button 
                @click="
                    open = false;
                    editPrivacyModal = true;
                "
                class="w-full flex justify-between items-center px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700 active:bg-zinc-200 dark:active:bg-zinc-600"
            >
                Edit privacy
                <i class="fa-solid fa-lock text-lg ml-2"></i>
            </button>

            <button 
                @click="showDeleteModal = true; open = false"
                class="w-full flex justify-between items-center px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700 active:bg-zinc-200 dark:active:bg-zinc-600 text-red-500"
            >
                Delete
                <i class="fa-solid fa-trash text-lg ml-2"></i>
            </button>

        @else
            <button 
                wire:click="reportPost"
                class="w-full flex justify-between items-center px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700 active:bg-zinc-200 dark:active:bg-zinc-600"
            >
                Report
                <i class="fa-solid fa-flag text-lg ml-2"></i>
            </button>

            <button 
                wire:click="savePost"
                class="w-full flex justify-between items-center px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700 active:bg-zinc-200 dark:active:bg-zinc-600"
            >
                @if ($isSaved)
                    Unsave
                    <i class="fa-solid fa-bookmark text-red-400 text-lg ml-2"></i>
                @else
                    Save
                    <i class="fa-solid fa-bookmark text-lg ml-2"></i>
                @endif
            </button>

            <button 
                class="w-full flex justify-between items-center px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700 active:bg-zinc-200 dark:active:bg-zinc-600"
                wire:click="toggleFollow"
            >
                @if ($isFollowing)
                    <p class="truncate">
                        Unfollow {{$post->user->name}}
                    </p>
                    <i class="fa fa-user-minus text-lg ml-2"></i>
                @elseif ($isFollowBack)
                    <p class="truncate">
                        Follow Back {{$post->user->name}}
                    </p>
                    <i class="fa fa-user-plus text-lg ml-2"></i>
                @else
                    <p class="truncate">
                        Follow {{$post->user->name}}
                    </p>

                    <i class="fa fa-user-plus text-lg ml-2"></i>
                @endif

            </button>

            <button 
                wire:click="reportPost"
                class="w-full flex justify-between items-center px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700 active:bg-zinc-200 dark:active:bg-zinc-600"
            >
                <p class="truncate">Block {{ $post->user->name }}</p>
                <i class="fa-solid fa-user-slash text-lg ml-2"></i>
            </button>
        @endif

        <button 
            @click="
                const textToCopy = window.location.href; 
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(textToCopy);
                } else {
                    // fallback for non-HTTPS
                    const textarea = document.createElement('textarea');
                    textarea.value = textToCopy;
                    textarea.style.position = 'fixed';
                    textarea.style.left = '-9999px';
                    document.body.appendChild(textarea);
                    textarea.focus();
                    textarea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textarea);
                }
                const dotOptions = document.getElementById('dotOptions');
                dotOptions.classList.remove('bg-zinc-200', 'dark:bg-zinc-800');
                open = false;
                copyToast = true;
                setTimeout(() => { copyToast = false }, 2000);
            "
            class="w-full flex justify-between items-center px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700 active:bg-zinc-200 dark:active:bg-zinc-600"
        >
            Copy link
            <i class="fa-solid fa-link text-lg ml-2"></i>
        </button>
    </div>

    {{-- Toast Notification --}}
    <div x-show="copyToast" x-transition class="fixed top-4 left-0 right-0 flex justify-center z-99999">
        <span class="px-2 py-1 bg-green-500 rounded-lg text-white font-bold">
            Copied!
        </span>
    </div>

    {{-- DELETE MODAL --}}
    <div 
        x-show="showDeleteModal"
        x-transition
        class="fixed inset-0 bg-black/80 flex justify-center items-center z-50"
    >
        <div class="bg-white sm:w-full md:w-2/3 lg:w-160 dark:bg-zinc-800 rounded-xl p-3 w-96 flex flex-col gap-2">
            <h2 class="text-lg font-semibold mb-2 p-3">Delete Post?</h2>
            <div class="p-3">
                <div class="flex items-center gap-4">
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
                            </p>
                        </div>
                    </div>

                </div>
                <p class="py-2 font-semibold text-xl">{{ $post->caption }}</p>
                <p>{!! nl2br(e($post->description)) !!}</p>
            </div>

            @if ($post->attachments()->count() >= 1)
                <livewire:attachments.post :post="$post" />
            @endif
            <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4 px-3">
                Are you sure you want to delete this post? This action cannot be undone.
            </p>
            <div class="flex justify-end gap-3 p-3">
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
        class="modal-bg fixed inset-0 bg-black/80 flex justify-center items-center z-50"
    >
        <div class="bg-white sm:w-full md:w-2/3 lg:w-160 dark:bg-zinc-800 rounded-xl p-6 w-96">
            <h2 class="text-lg font-semibold mb-3">Edit Post</h2>
            <input 
                wire:model.defer="editedCaption"
                type="text"
                class="w-full rounded-lg p-2 mb-3 bg-zinc-100 dark:bg-zinc-700 focus:outline-none"
                value="{{ $post->caption }}"
                >
            <textarea 
                wire:model.defer="editedContent"
                class="w-full h-28 rounded-lg p-2 bg-zinc-100 dark:bg-zinc-700 focus:outline-none resize-none"
            >{!! nl2br(e($post->description)) !!}</textarea>
            
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

    {{-- PRIVACY MODAL --}}
    <div 
        x-show="editPrivacyModal"
        x-transition
        @click.away="editPrivacyModal = false"
        class="fixed inset-0 bg-black/80 flex justify-center items-center z-50"
    >
        <div class="bg-white sm:w-full md:w-2/3 lg:w-160 dark:bg-zinc-800 rounded-xl w-96 flex flex-col gap-2 p-3">
            <div class="p-3">
                <div class="flex items-center gap-3">
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
                            </p>
                        </div>
                    </div>

                <div>
                    <x:select
                        wire:model.defer="editedPrivacy"
                    >
                        <option value="0">Public</option>
                        <option value="1">Followers</option>
                        <option value="2">Only Me</option>
                    </x:select>
                </div>

                </div>
                <p class="py-2 font-semibold text-xl">{{ $post->caption }}</p>
                <p>{!! nl2br(e($post->description)) !!}</p>
            </div>

            @if ($post->attachments()->count() >= 1)
                <livewire:attachments.post :post="$post" />
            @endif

            <div class="flex justify-end gap-3 mt-4 p-3">
                <flux:button @click="editPrivacyModal = false">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button variant="primary" wire:click="updatePrivacy">
                    {{ __('Save') }}
                </flux:button>
            </div>
        </div>
    </div>
</div>