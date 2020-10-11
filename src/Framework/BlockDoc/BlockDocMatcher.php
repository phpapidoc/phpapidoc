<?php

namespace PhpApiDoc\Framework\BlockDoc;

final class BlockDocMatcher
{
    private $pattern = '|/\*.*?\*/|is';

    /**
     * @param string $contents
     *
     * @return array
     */
    public function matchBlockDocs($contents)
    {
        $docs = [];
        if (preg_match_all($this->pattern, $contents, $matches)) {
            if (isset($matches[0]) && count($matches[0])) {
                $docs = $matches[0];
            }
        }

        return $docs;
    }
}
