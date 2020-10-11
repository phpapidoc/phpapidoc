<?php

namespace PhpApiDoc\Framework\BlockDoc;

final class BlockCollectionIterator implements \Countable, \Iterator
{
    /**
     * @var BlockDoc[]
     */
    private $docs = [];

    /**
     * @var int
     */
    private $position;

    /**
     * BlockCollectionIterator constructor.
     * @param BlockDocCollection $docs
     */
    public function __construct($docs)
    {
        $this->docs = $docs->asArray();
    }

    public function current()
    {
        return $this->docs[$this->position];
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
        return $this->position < count($this->docs);
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
