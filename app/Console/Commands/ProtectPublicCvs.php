<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ProtectPublicCvs extends Command
{
    protected $signature = 'stagelink:protect-cvs';

    protected $description = 'Move existing CV files from public storage to private storage';

    public function handle(): int
    {
        $public = Storage::disk('public');
        $private = Storage::disk('private');
        $hasErrors = false;

        foreach ($public->allFiles('cvs') as $path) {
            $createdPrivateCopy = false;

            if ($private->exists($path)) {
                if ($private->checksum($path) !== $public->checksum($path)) {
                    $this->error("Conflicting private file; public copy kept: {$path}");
                    $hasErrors = true;

                    continue;
                }
            } else {
                $stream = $public->readStream($path);
                if (! is_resource($stream)) {
                    $this->error("Unable to read public CV; file kept: {$path}");
                    $hasErrors = true;

                    continue;
                }

                try {
                    $createdPrivateCopy = $private->writeStream($path, $stream);
                } finally {
                    fclose($stream);
                }

                if (! $createdPrivateCopy || ! $private->exists($path)
                    || $private->checksum($path) !== $public->checksum($path)) {
                    if ($createdPrivateCopy) {
                        $private->delete($path);
                    }

                    $this->error("Unable to verify private copy; public file kept: {$path}");
                    $hasErrors = true;

                    continue;
                }
            }

            if (! $public->delete($path)) {
                $this->error("Unable to remove public CV copy: {$path}");
                $hasErrors = true;

                continue;
            }

            $this->info("Protected {$path}");
        }

        return $hasErrors ? self::FAILURE : self::SUCCESS;
    }
}
