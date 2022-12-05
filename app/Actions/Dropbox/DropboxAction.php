<?php

namespace App\Actions\Dropbox;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use League\Flysystem\FilesystemException;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToRetrieveMetadata;

class DropboxAction
{

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
        if (!$name) $name = md5($this->context . time());
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
        $path = $this->context && $this->context !== '' ? "{$this->context}/{$path}" : $path;
        return $this->disk()->delete($path);
    }

    /**
     * upload file
     * @return bool
     */
    public static function deleteFile($path)
    {
        return self::getDisk()->delete($path);
    }

    /**
     * view file
     */
    public static function view($path)
    {
        try {
            $response = self::getDisk()->read($path);
            return $response;
        } catch (FilesystemException | UnableToReadFile $exception) {
            throw $exception;
        }
    }

    /**
     * mimeType file
     */
    public static function mimeType($path)
    {
        try {
            $response = self::getDisk()->mimeType($path);
            return $response;
        } catch (
            FilesystemException |
            UnableToRetrieveMetadata $exception
        ) {
            throw $exception;
        }
    }

    /**
     * fileSize file
     */
    public static function fileSize($path)
    {
        try {
            $response = self::getDisk()->fileSize($path);
            return $response;
        } catch (
            FilesystemException |
            UnableToRetrieveMetadata $exception
        ) {
            throw $exception;
        }
    }

    /**
     * download file
     */
    public static function download($path)
    {
        try {
            $response = self::getDisk()->readStream($path);
            return $response;
        } catch (FilesystemException | UnableToReadFile $exception) {
            throw $exception;
        }
    }
}
