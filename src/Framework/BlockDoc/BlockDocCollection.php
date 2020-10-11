<?php

namespace PhpApiDoc\Framework\BlockDoc;

final class BlockDocCollection implements \Countable, \IteratorAggregate
{
    private $block_docs = [];

    /**
     * @param BlockDoc[] $block_docs
     * @return BlockDocCollection
     */
    public static function fromArray($block_docs)
    {
        return new self(...$block_docs);
    }

    /**
     * BlockDocCollection constructor.
     * @param BlockDoc[] $block_docs
     */
    public function __construct(...$block_docs)
    {
        $this->block_docs = $block_docs;
    }

    public function asArray()
    {
        return $this->block_docs;
    }

    public function getIterator()
    {
        return new BlockCollectionIterator($this);
    }

    public function count()
    {
        return count($this->block_docs);
    }

    public function isEmpty()
    {
        return $this->count() === 0;
    }
}
