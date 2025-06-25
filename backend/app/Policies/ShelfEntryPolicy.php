<?php

namespace App\Policies;

use App\Models\ShelfEntry;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShelfEntryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ShelfEntry $shelfEntry): bool
    {
        return $shelfEntry->user_id == $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, ShelfEntry $shelfEntry): bool
    {
        return $shelfEntry->user_id == $user->id;
    }

    public function delete(User $user, ShelfEntry $shelfEntry): bool
    {
        return $shelfEntry->user_id == $user->id;
    }
}
