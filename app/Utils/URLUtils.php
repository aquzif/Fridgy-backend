<?php

namespace App\Utils;

class URLUtils {

    static function isValidYoutubeUrl($url) {
        $pattern = '/^(http(s)?:\/\/)?((w){3}.)?youtu(be|.be)?(\.com)?\/.+/';
        return preg_match($pattern, $url);
    }

}
