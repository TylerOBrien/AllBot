<?php

namespace App\Policies\Api\v1;

use App\Models\{ Folder, User };

use Illuminate\Auth\Access\HandlesAuthorization;

class FolderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any folders.
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

        return $user->hasAbility('index', Folder::class);
    }

    /**
     * Determine whether the user can view the folder.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Folder  $folder
     *
     * @return mixed
     */
    public function show(User $user, Folder $folder)
    {
        if ($folder->owner_type === User::class && $user->id === $folder->owner_id) {
            return true;
        }

        if ($user->is_admin) {
            return true;
        }

        if ($folder->owner && $user->can('show', $folder->owner)) {
            return true;
        }

        return $user->hasAbility('show', Folder::class);
    }

    /**
     * Determine whether the user can create folders.
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
     * Determine whether the user can update the folder.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Folder  $folder
     *
     * @return mixed
     */
    public function update(User $user, Folder $folder)
    {
        if ($folder->owner_type === User::class && $user->id === $folder->owner_id) {
            return true;
        }

        if ($user->is_admin) {
            return true;
        }

        if ($folder->owner && $user->can('update', $folder->owner)) {
            return true;
        }

        return $user->hasAbility('update', Folder::class);
    }

    /**
     * Determine whether the user can delete the folder.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Folder  $folder
     *
     * @return mixed
     */
    public function destroy(User $user, Folder $folder)
    {
        if ($folder->owner_type === User::class && $user->id === $folder->owner_id) {
            return true;
        }

        if ($user->is_admin) {
            return true;
        }

        if ($folder->owner && $user->can('destroy', $folder->owner)) {
            return true;
        }

        return $user->hasAbility('destroy', Folder::class);
    }
}
