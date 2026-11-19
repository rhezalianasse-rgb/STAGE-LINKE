<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class Controller
{
    protected function streamPrivateCv(?string $path): StreamedResponse
    {
        abort_unless(
            is_string($path)
                && str_starts_with($path, 'cvs/')
                && basename($path) === substr($path, 4),
            404
        );

        $disk = Storage::disk('private');
        abort_unless($disk->exists($path), 404);

        return $disk->download($path, basename($path));
    }
}
