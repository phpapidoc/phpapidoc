<?php

namespace PhpApiDoc\Framework\BlockDoc;

final class BlockDoc
{
    /**
     * @var string
     */
    private $block_doc;

    /**
     * BlockDoc constructor.
     * @param string $block_doc
     */
    public function __construct($block_doc)
    {
        $this->block_doc = $block_doc;
    }

    /**
     * @return string
     */
    public function getBlockDoc()
    {
        return $this->block_doc;
    }
}
