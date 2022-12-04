<?php

namespace App\Actions\Dropbox;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class DropboxAction {

    use HasDisk;

    protected $context = '';

    public function __construct($context = '')
    {
        $this->context = $context;
    }

    /**
     * upload file
     */
    public function upload(UploadedFile $file, $name = null)
    {
        if (!$name) $name = md5($this->context.time());
        $filename = trim(Str::replace(['/', '\\'], ['-', '-'], $name));
        $filename = "{$filename}.{$file->getClientOriginalExtension()}";
        $path = $this->context && $this->context !== '' ? "{$this->context}/{$filename}" : $filename;
        $this->disk()->write($path, file_get_contents($file->getRealPath()));
        return $path;
    }

    /**
     * upload file
     * @return bool
     */
    public function delete($path)
    {
        return $this->disk()->delete($path);
    }
}