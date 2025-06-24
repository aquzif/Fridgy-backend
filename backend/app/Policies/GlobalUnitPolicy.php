<?php

namespace App\Policies;

use App\Models\GlobalUnit;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GlobalUnitPolicy {
    use HandlesAuthorization;

    public function viewAny(User $user): bool {
        return true;
    }

    public function view(User $user, GlobalUnit $globalUnit): bool {
        return true;
    }

    public function create(User $user): bool {
        return true;
    }

    public function update(User $user, GlobalUnit $globalUnit): bool {
        return true;
    }

    public function delete(User $user, GlobalUnit $globalUnit): bool {
        return true;
    }

}
