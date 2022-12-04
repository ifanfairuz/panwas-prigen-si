<?php

namespace App\Actions\Dropbox;

use Illuminate\Support\Facades\Storage;

trait HasDisk {

    /**
     * get disk
     * @return \Illuminate\Contracts\Filesystem\Filesystem|\League\Flysystem\Filesystem
     */
    protected function disk()
    {
        return Storage::disk("dropbox");
    }
}