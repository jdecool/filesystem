<?php

declare(strict_types=1);

namespace JDecool\Filesystem;

use CallbackFilterIterator;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use SplFileInfo;

class Filesystem implements FilesystemIterator
{
    public static function browse(string $path): FilesystemIterator
    {
        if (!file_exists($path)) {
            throw InvalidPath::fromPath($path);
        }

        if (false === ($realPath = realpath($path))) {
            throw new \InvalidArgumentException("Invalid '$path' path.");
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($realPath, \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::FOLLOW_SYMLINKS),
            RecursiveIteratorIterator::SELF_FIRST,
        );

        return new self(
            new CallbackFilterIterator($iterator, static fn (): bool => true),
        );
    }

    private function __construct(
        private CallbackFilterIterator $iterator,
    ) {
    }

    public function files(): FilesystemIterator
    {
        return new self(
            new CallbackFilterIterator($this->iterator, static fn (SplFileInfo $file): bool => $file->isFile()),
        );
    }

    public function directories(): FilesystemIterator
    {
        return new self(
            new CallbackFilterIterator($this->iterator, static fn (SplFileInfo $file): bool => $file->isDir()),
        );
    }

    public function regexp(string $pattern): FilesystemIterator
    {
        return new self(
            new CallbackFilterIterator($this->iterator, static function (SplFileInfo $file) use ($pattern): bool {
                /** @phpstan-ignore-next-line */
                $result = preg_match($pattern, $file->getRealPath());

                return is_int($result) && $result > 0;
            }),
        );
    }

    public function current(): File
    {
        return $this->createFile($this->iterator->current());
    }

    public function next(): void
    {
        $this->iterator->next();
    }

    public function key(): string
    {
        return $this->current()->fullpath();
    }

    public function valid(): bool
    {
        return $this->iterator->valid();
    }

    public function rewind(): void
    {
        $this->iterator->rewind();
    }

    private function createFile(SplFileInfo $file): File
    {
        if ($file->isDir()) {
            return new Directory($file);
        }

        return new File($file);
    }
}
