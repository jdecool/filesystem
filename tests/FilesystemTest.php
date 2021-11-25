<?php

declare(strict_types=1);

namespace JDecool\Filesystem\Tests;

use JDecool\Filesystem\Filesystem;
use JDecool\Filesystem\FilesystemIterator;

class FilesystemTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        if (!file_exists('fixtures/empty')) {
            mkdir('fixtures/empty', 0777, true);
        }
    }

    /**
     * @dataProvider filesAndDirectories
     */
    public function testParseDirectory(string $path, array $expected): void
    {
        $iterator = Filesystem::browse($path);
        static::assertInstanceOf(FilesystemIterator::class, $iterator);
        static::assertFilesystemExpectation($iterator, $expected);
    }

    /**
     * @dataProvider directories
     */
    public function testParseDirectoryFiles(string $path, array $expected): void
    {
        $iterator = Filesystem::browse($path)->files();
        static::assertInstanceOf(FilesystemIterator::class, $iterator);
        static::assertFilesystemExpectation($iterator, $expected);
    }

    /**
     * @dataProvider files
     */
    public function testParseDirectoryDirectories(string $path, array $expected): void
    {
        $iterator = Filesystem::browse($path)->directories();
        static::assertInstanceOf(FilesystemIterator::class, $iterator);
        static::assertFilesystemExpectation($iterator, $expected);
    }

    public function testParseFilteredDirectory(): void
    {
        $iterator = Filesystem::browse('fixtures')->regexp('/.*\.md/');
        static::assertInstanceOf(FilesystemIterator::class, $iterator);
        static::assertFilesystemExpectation($iterator, [
            'fixtures/directory/subdirectory/another-markdown.md',
            'fixtures/markdown.md',
        ]);
    }

    public function testCombineFilter(): void
    {
        $iterator = Filesystem::browse('fixtures')->files()->regexp('/.*another-markdown.*/');
        static::assertInstanceOf(FilesystemIterator::class, $iterator);
        static::assertFilesystemExpectation($iterator, [
            'fixtures/directory/subdirectory/another-markdown.md',
        ]);
    }

    public function filesAndDirectories(): iterable
    {
        yield 'empty directory' => [
            'fixtures/empty',
            [],
        ];

        yield 'browse files & directories' => [
            'fixtures',
            [
                'fixtures/directory',
                'fixtures/directory/subdirectory',
                'fixtures/directory/subdirectory/another-file.txt',
                'fixtures/directory/subdirectory/another-markdown.md',
                'fixtures/directory/file-directory1.txt',
                'fixtures/empty',
                'fixtures/file1.txt',
                'fixtures/file2.txt',
                'fixtures/markdown.md',
            ],
        ];
    }

    public function directories(): iterable
    {
        yield 'empty directory' => [
            'fixtures/empty',
            [],
        ];

        yield 'browse directories' => [
            'fixtures',
            [
                'fixtures/directory/subdirectory/another-file.txt',
                'fixtures/directory/subdirectory/another-markdown.md',
                'fixtures/directory/file-directory1.txt',
                'fixtures/file1.txt',
                'fixtures/file2.txt',
                'fixtures/markdown.md',
            ],
        ];
    }

    public function files(): iterable
    {
        yield 'empty directory' => [
            'fixtures/empty',
            [],
        ];

        yield 'browse files' => [
            'fixtures',
            [
                'fixtures/directory',
                'fixtures/directory/subdirectory',
                'fixtures/empty',
            ],
        ];
    }
}
