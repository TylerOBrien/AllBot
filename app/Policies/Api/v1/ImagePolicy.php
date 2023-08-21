<?php

namespace App\Policies\Api\v1;

use App\Models\{ Image, User };

use Illuminate\Auth\Access\HandlesAuthorization;

class ImagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any images.
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

        return $user->hasAbility('index', Image::class);
    }

    /**
     * Determine whether the user can view the image.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Image  $image
     *
     * @return mixed
     */
    public function show(User $user, Image $image)
    {
        if ($image->owner_type === User::class && $user->id === $image->owner_id) {
            return true;
        }

        if ($user->is_admin) {
            return true;
        }

        if ($image->owner && $user->can('show', $image->owner)) {
            return true;
        }

        return $user->hasAbility('show', Image::class);
    }

    /**
     * Determine whether the user can create images.
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
     * Determine whether the user can update the image.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Image  $image
     *
     * @return mixed
     */
    public function update(User $user, Image $image)
    {
        if ($image->owner_type === User::class && $user->id === $image->owner_id) {
            return true;
        }

        if ($user->is_admin) {
            return true;
        }

        if ($image->owner && $user->can('update', $image->owner)) {
            return true;
        }

        return $user->hasAbility('update', Image::class);
    }

    /**
     * Determine whether the user can delete the image.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Image  $image
     *
     * @return mixed
     */
    public function destroy(User $user, Image $image)
    {
        if ($image->owner_type === User::class && $user->id === $image->owner_id) {
            return true;
        }

        if ($user->is_admin) {
            return true;
        }

        if ($image->owner && $user->can('destroy', $image->owner)) {
            return true;
        }

        return $user->hasAbility('destroy', Image::class);
    }
}
