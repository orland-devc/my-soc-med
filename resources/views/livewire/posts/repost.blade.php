<?php

use App\Models\Comment;
use Livewire\Volt\Component;

new class extends Component {
    public Comment $comment;

    public function reply(): void {
        // Just emit a browser event — no DB logic.
        $this->dispatch('showUnavailableNotice');
    }
};
?>

<div>
    <button
        wire:click="reply"
        class="cursor-pointer flex items-center gap-1 hover:scale-110 transition-all"
    >
        <p class="text-md mr-1">Reply</p>
    </button>

    <script>
        window.addEventListener('showUnavailableNotice', () => {
            // Simple toast — you can replace this with SweetAlert, Toastify, etc.
            const toast = document.createElement('div');
            toast.textContent = 'Sorry, this is currently unavailable.';
            toast.style.position = 'fixed';
            toast.style.bottom = '20px';
            toast.style.right = '20px';
            toast.style.background = '#ff6961';
            toast.style.color = 'white';
            toast.style.padding = '10px 20px';
            toast.style.borderRadius = '8px';
            toast.style.fontSize = '14px';
            toast.style.boxShadow = '0 2px 8px rgba(0,0,0,0.2)';
            toast.style.zIndex = '9999';
            document.body.appendChild(toast);

            setTimeout(() => toast.remove(), 5000);
        });
    </script>
</div>
