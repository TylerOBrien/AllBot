<?php

namespace App\Policies\Api\v1;

use App\Models\{ Sound, User };

use Illuminate\Auth\Access\HandlesAuthorization;

class SoundPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any sounds.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function index(User $user)
    {
        if ($user->is_admin) {
            return true;
        }

        return $user->hasAbility('index', Sound::class);
    }

    /**
     * Determine whether the user can view the sound.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Sound  $sound
     *
     * @return mixed
     */
    public function show(User $user, Sound $sound)
    {
        if ($sound->owner_type === User::class && $user->id === $sound->owner_id) {
            return true;
        }

        if ($user->is_admin) {
            return true;
        }

        if ($sound->owner && $user->can('show', $sound->owner)) {
            return true;
        }

        return $user->hasAbility('show', Sound::class);
    }

    /**
     * Determine whether the user can create sounds.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function store(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the sound.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Sound  $sound
     *
     * @return mixed
     */
    public function update(User $user, Sound $sound)
    {
        if ($sound->owner_type === User::class && $user->id === $sound->owner_id) {
            return true;
        }

        if ($user->is_admin) {
            return true;
        }

        if ($sound->owner && $user->can('update', $sound->owner)) {
            return true;
        }

        return $user->hasAbility('update', Sound::class);
    }

    /**
     * Determine whether the user can delete the sound.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Sound  $sound
     *
     * @return mixed
     */
    public function destroy(User $user, Sound $sound)
    {
        if ($sound->owner_type === User::class && $user->id === $sound->owner_id) {
            return true;
        }

        if ($user->is_admin) {
            return true;
        }

        if ($sound->owner && $user->can('destroy', $sound->owner)) {
            return true;
        }

        return $user->hasAbility('destroy', Sound::class);
    }
}
