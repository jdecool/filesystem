<?php

declare(strict_types=1);

namespace JDecool\Filesystem\Tests;

use JDecool\Filesystem\File;
use SplFileInfo;

class FileTest extends TestCase
{
    private SplFileInfo $fixture;
    private File $file;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fixture = new SplFileInfo(realpath(__DIR__.'/../fixtures/markdown.md'));
        $this->file = new File($this->fixture);
    }

    public function testGetFullpath(): void
    {
        static::assertSame($this->fixture->getPathname(), $this->file->fullpath());
    }

    public function testGetFilenameWithoutExtension(): void
    {
        static::assertSame('markdown', $this->file->nameWithoutExtension());
    }
}
