<?php

namespace App\Utils;

class MathUtils {

    static function roundUp($value, $precision = 2) {
        $pow = pow ( 10, $precision );
        return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow;
    }

}
