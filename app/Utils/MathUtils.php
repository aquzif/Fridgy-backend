<?php

namespace App\Utils;

class MathUtils {

    static function roundUp($number, $precision = 2) {
        $fig = (int) str_pad('1', $precision, '0');
        return (ceil($number * $fig) / $fig);
    }

}
