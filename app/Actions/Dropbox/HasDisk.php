<?php

namespace App\Actions\Dropbox;

use Illuminate\Support\Facades\Storage;

trait HasDisk
{

    /**
     * get disk
     * @return \Illuminate\Contracts\Filesystem\Filesystem|\League\Flysystem\Filesystem
     */
    protected function disk()
    {
        return self::getDisk();
    }

    /**
     * get disk
     * @return \Illuminate\Contracts\Filesystem\Filesystem|\League\Flysystem\Filesystem
     */
    protected static function getDisk()
    {
        return Storage::disk("dropbox");
    }
}
