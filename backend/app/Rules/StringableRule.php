<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StringableRule implements Rule {
    public function __construct() {
    }

    public function passes($attribute, $value): bool {
        return preg_match('/^[a-z0-9\s]*$/i', $value);
    }

    public function message(): string {
        return 'This field must be stringable';
    }
}
