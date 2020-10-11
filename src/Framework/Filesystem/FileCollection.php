<?php

namespace PhpApiDoc\Framework\Filesystem;

use Exception;
use Traversable;

final class FileCollection implements \Countable, \IteratorAggregate
{

    private $files = [];

    /**
     * @param File[] $files
     * @return FileCollection
     */
    public static function fromArray($files)
    {
        return new self(...$files);
    }

    /**
     * FileCollection constructor.
     * @param File[] $files
     */
    public function __construct(...$files)
    {
        $this->files = $files;
    }

    public function getIterator()
    {
        return new FileCollectionIterator($this);
    }

    public function count()
    {
        return count($this->files);
    }

    public function isEmpty()
    {
        return 0 === $this->count();
    }

    public function asArray()
    {
        return $this->files;
    }
}