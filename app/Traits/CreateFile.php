<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;

trait CreateFile
{
    public function checkAndCreateFile($path, $content = '')
    {
        if (!File::exists($path)) {
            File::put($path, $content);
        } else {
            // File::replace($path, $content);
            return false;
        }

        return true;
    }
}
