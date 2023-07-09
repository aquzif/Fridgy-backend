<?php

    namespace App\Policies;

    use App\Models\GlobalUnit;
    use App\Models\User;
    use Illuminate\Auth\Access\HandlesAuthorization;

    class GlobalUnitPolicy {
        use HandlesAuthorization;

        public function viewAny(User $user): bool {

        }

        public function view(User $user, GlobalUnit $globalUnit): bool {
        }

        public function create(User $user): bool {
        }

        public function update(User $user, GlobalUnit $globalUnit): bool {
        }

        public function delete(User $user, GlobalUnit $globalUnit): bool {
        }

        public function restore(User $user, GlobalUnit $globalUnit): bool {
        }

        public function forceDelete(User $user, GlobalUnit $globalUnit): bool {
        }
    }
