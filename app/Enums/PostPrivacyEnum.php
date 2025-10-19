<?php

namespace App\Enums;

enum PostPrivacyEnum: string
{
    case PUBLIC = 'public';
    case FOLLOWERS = 'followers';
    case ONLY_ME = 'only_me';

    public function getLabel(): string
    {
        return match ($this) {
            self::PUBLIC => 'Public',
            self::FOLLOWERS => 'Followers',
            self::ONLY_ME => 'Only_me',
        };
    }
}
