<?php

namespace App\Policies;

use App\Models\ShoppingList;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\ShoppingListEntry;
use function Psy\sh;
use function Symfony\Component\String\s;

class ShoppingListEntryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool {
        return true;
    }

    public function view(User $user, ShoppingListEntry $shoppingListEntry, ShoppingList $shoppingList): bool {
        return $shoppingListEntry->getShoppingList()->first()->user_id == $user->id
            && $shoppingListEntry->shopping_list_id = $shoppingList->id;
    }

    public function create(User $user): bool {
        return true;
    }

    public function update(User $user, ShoppingListEntry $shoppingListEntry, ShoppingList $shoppingList): bool {
        return $shoppingListEntry->getShoppingList()->first()->user_id == $user->id
            && $shoppingListEntry->shopping_list_id = $shoppingList->id;
    }

    public function delete(User $user, ShoppingListEntry $shoppingListEntry, ShoppingList $shoppingList): bool {
        return $shoppingListEntry->getShoppingList()->first()->user_id == $user->id
            && $shoppingListEntry->shopping_list_id = $shoppingList->id;
    }

}
