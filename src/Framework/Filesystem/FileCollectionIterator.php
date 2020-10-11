<?php

namespace PhpApiDoc\Framework\Filesystem;

final class FileCollectionIterator implements \Countable, \Iterator
{

    /**
     * @var File[]
     */
    private $files = [];

    /**
     * @var int
     */
    private $position;

    /**
     * FileCollectionIterator constructor.
     * @param FileCollection $files
     */
    public function __construct($files)
    {
        $this->files = $files->asArray();
    }

    public function current()
    {
        return $this->files[$this->position];
    }

    public function next()
    {
        $this->position++;
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return $this->position < count($this->files);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function count()
    {
        return iterator_count($this);
    }
}