<?php

namespace App\Utils;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorageUtils {

    public static function storeImage($uplaodedFile) {
        $newFileName = Str::random(40) . '.' . $uplaodedFile->getClientOriginalExtension();
        Storage::put('/public/images/' . $newFileName, $uplaodedFile->getContent());
        return '/storage/images/' . $newFileName;
    }

}
