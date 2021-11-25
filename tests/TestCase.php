<?php

declare(strict_types=1);

namespace JDecool\Filesystem\Tests;

use JDecool\Filesystem\FilesystemIterator;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected static string $basePath;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        static::$basePath = realpath(__DIR__.'/..');
    }

    protected static function assertFilesystemExpectation(FilesystemIterator $iterator, array $expected): void
    {
        $expectations = (new ArrayObject($expected))
            ->map(static fn (string $path): string => rtrim(self::$basePath, '/').'/'.ltrim($path, '/'))
            ->flip()
            ->map(static fn (): bool => false);

        foreach ($iterator as $file) {
            static::assertArrayHasKey($file->fullpath(), $expectations, "Not expected '{$file->fullpath()}' file.");
            $expectations[$file->fullpath()] = true;
        }

        foreach ($expectations as $file => $exists) {
            static::assertTrue($exists, "File '$file' expected but not found.");
        }
    }
}
