<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProductUnitPolicy {
    use HandlesAuthorization;


    public function viewAny(User $user): bool {
        return true;
    }

    public function view(User $user, ProductUnit $productUnit, Product $product): bool {
        return $product->id == $productUnit->product_id;
    }

    public function create(User $user): bool {
        return true;
    }

    public function update(User $user, ProductUnit $productUnit, Product $product): bool {
        return $product->id == $productUnit->product_id;
    }

    public function delete(User $user, ProductUnit $productUnit, Product $product): bool {
        return $product->id == $productUnit->product_id;
    }

}
