<?php

namespace PhpApiDoc\Framework\Filesystem;

final class DirectoryCollection implements \Countable
{
    /**
     * @var Directory[]
     */
    private $directories;

    public static function fromArray($directories)
    {
        return new self(...$directories);
    }

    private function __construct(...$directories)
    {
        $this->directories = $directories;
    }

    public function asArray()
    {
        return $this->directories;
    }

    public function count()
    {
        return count($this->directories);
    }

    public function isEmpty()
    {
        return $this->count() === 0;
    }
}
