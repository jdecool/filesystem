<?php

declare(strict_types=1);

namespace JDecool\Filesystem;

use SplFileInfo;

class File
{
    public function __construct(
        private SplFileInfo $file,
    ) {
    }

    public function fullpath(): string
    {
        return $this->file->getPathname();
    }

    public function nameWithoutExtension(): string
    {
        return $this->file->getBasename(".{$this->file->getExtension()}");
    }
}
