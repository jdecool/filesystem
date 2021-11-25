<?php

declare(strict_types=1);

namespace JDecool\Filesystem;

use RuntimeException;

class InvalidPath extends RuntimeException
{
    public static function fromPath(string $path): self
    {
        return new self("Invalid '$path' path.");
    }
}
