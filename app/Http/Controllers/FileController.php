<?php

namespace App\Http\Controllers;

use App\Actions\Dropbox\DropboxAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{

    /**
     * view file
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function view(Request $request)
    {
        $path = $request->query('path');
        $provider = $request->query('provider', 'local');

        switch ($provider) {
            case 'local':
                $size = Storage::size($path);
                return response()->file($path, [
                    'Content-Length' => $size,
                ]);
                break;

            case 'dropbox':
                $filename = pathinfo($path, PATHINFO_BASENAME);
                $mime = DropboxAction::mimeType($path);
                $size = DropboxAction::fileSize($path);
                return response(DropboxAction::view($path), 200, [
                    'Content-Type' => $mime,
                    'Content-Length' => $size,
                    'Content-Disposition' => 'inline; filename="' . $filename . '"'
                ]);
                break;
        }

        return redirect()->back();
    }

    /**
     * download file
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function download(Request $request)
    {
        $path = $request->query('path');
        $provider = $request->query('provider', 'local');
        $filename = pathinfo($path, PATHINFO_BASENAME);

        switch ($provider) {
            case 'local':
                $size = Storage::size($path);
                return response()->file($path, [
                    'Content-Length' => $size,
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"'
                ]);
                break;

            case 'dropbox':
                $mime = DropboxAction::mimeType($path);
                $size = DropboxAction::fileSize($path);
                return response()->streamDownload(function () use ($path) {
                    echo DropboxAction::download($path);
                }, $filename, [
                    'Content-Type' => $mime,
                    'Content-Length' => $size,
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"'
                ]);
                break;
        }

        return redirect()->back();
    }
}
