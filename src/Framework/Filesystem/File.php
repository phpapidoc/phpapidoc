<?php

namespace PhpApiDoc\Framework\Filesystem;

final class File
{
    /**
     * @var string
     */
    private $path;

    /**
     * File constructor.
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return false|string
     */
    public function getContents()
    {
        return file_get_contents($this->getPath());
    }
}