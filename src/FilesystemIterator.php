<?php

declare(strict_types=1);

namespace JDecool\Filesystem;

use Iterator;

/**
 * @extends Iterator<string, File|Directory>
 */
interface FilesystemIterator extends Iterator
{
    /**
     * @return FilesystemIterator<string, File|Directory>
     */
    public static function browse(string $path): FilesystemIterator;

    /**
     * @return FilesystemIterator<string, File|Directory>
     */
    public function files(): FilesystemIterator;

    /**
     * @return FilesystemIterator<string, File|Directory>
     */
    public function directories(): FilesystemIterator;

    /**
     * @return FilesystemIterator<string, File|Directory>
     */
    public function regexp(string $pattern): FilesystemIterator;
}
