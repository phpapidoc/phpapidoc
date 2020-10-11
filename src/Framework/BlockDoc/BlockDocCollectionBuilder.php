<?php

namespace PhpApiDoc\Framework\BlockDoc;

use PhpApiDoc\Framework\File\File;

final class BlockDocCollectionBuilder
{

    /**
     * @var BlockDocMatcher
     */
    private $matcher;

    public function __construct()
    {
        $this->matcher = new BlockDocMatcher();
    }

    /**
     * @param File $file
     *
     * @return BlockDocCollection
     */
    public function fromFile($file)
    {
        return BlockDocCollection::fromArray($this->matcher->matchBlockDocs($file->getContents()));
    }
}
