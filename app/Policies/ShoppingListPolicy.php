<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\ShoppingList;

class ShoppingListPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool {
        return true;
    }

    public function view(User $user, ShoppingList $shoppingList): bool {

        return $shoppingList->user_id == $user->id;
    }

    public function create(User $user): bool {
        return true;
    }

    public function update(User $user, ShoppingList $shoppingList): bool {
        return $shoppingList->user_id == $user->id;
    }

    public function delete(User $user, ShoppingList $shoppingList): bool {
        return $shoppingList->user_id == $user->id;
    }


}
