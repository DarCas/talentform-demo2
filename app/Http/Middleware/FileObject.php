<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class FileObject
{
    public function handle(Request $request, Closure $next): Response
    {
        $disk = Storage::disk('guestbook');
        $filename = $request->route()->parameters()['filename'];

        if ($disk->exists($filename)) {
            Session::put($filename, [
                'filepath' => $disk->path($filename),
                'filename' => $filename,
                'filesize' => $disk->size($filename),
                'filetype' => $disk->mimeType($filename),
                'lastModified' => $disk->lastModified($filename),
            ]);
        }

        return $next($request);
    }
}
