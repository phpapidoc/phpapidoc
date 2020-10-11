<?php

namespace PhpApiDoc\Framework\Filesystem;

final class Directory
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var string
     */
    private $suffix;

    /**
     * ApiDirectory constructor.
     * @param string $path
     * @param string $prefix
     * @param string $suffix
     */
    public function __construct($path, $prefix = null, $suffix = null)
    {
        $this->path = $path;
        $this->prefix = $prefix;
        $this->suffix = $suffix;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @return string
     */
    public function getSuffix()
    {
        return $this->suffix;
    }
}
