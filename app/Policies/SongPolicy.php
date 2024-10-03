<?php

namespace App\Policies;

use App\Models\Song;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SongPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the song.
     */
    public function view(User $user, Song $song)
    {
        return $user->id === $song->user_id;
    }

    /**
     * Determine whether the user can update the song.
     */
    public function update(User $user, Song $song)
    {
        return $user->id === $song->user_id;
    }

    /**
     * Determine whether the user can delete the song.
     */
    public function delete(User $user, Song $song)
    {
        return $user->id === $song->user_id;
    }

    // 必要に応じて他のメソッド（create, viewAny など）を追加できます
}