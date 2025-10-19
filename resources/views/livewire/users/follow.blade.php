<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public User $user;

    public bool $isFollowing = false;
    public bool $isFollowBack = false;

    public function mount(): void
    {
        $auth = Auth::user();
        $this->isFollowing = $auth->isFollowing($this->user);
        $this->isFollowBack = $this->user->isFollowing($auth);
    }

    public function toggleFollow(): void
    {
        $auth = Auth::user();

        if ($auth->isFollowing($this->user)) {
            $auth->following()->detach($this->user->id);
        } else {
            $auth->following()->attach($this->user->id);
        }

        // Refresh states
        $this->isFollowing = $auth->isFollowing($this->user);
        $this->isFollowBack = $this->user->isFollowing($auth);
    }
};
?>

<flux:button
    wire:click="toggleFollow"
    :variant="$isFollowing ? 'filled' : 'primary'"
>
    @if ($isFollowing)
        Unfollow 
        <i class="fa fa-user-minus"></i>
    @elseif ($isFollowBack)
        Follow Back
        <i class="fa fa-user-plus"></i>
    @else
        Follow
        <i class="fa fa-user-plus"></i>
    @endif
</flux:button>

