<?php

namespace PhpApiDoc\Framework\Configuration;

final class ApiPath
{
    private $directories;

    private $files;

    private $exclude;

    /**
     * ApiPaths constructor.
     * @param $directories
     * @param $files
     * @param $exclude
     */
    public function __construct($directories, $files, $exclude)
    {
        $this->directories = $directories;
        $this->files = $files;
        $this->exclude = $exclude;
    }

    public function getDirectories()
    {
        return $this->directories;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function getExclude()
    {
        return $this->exclude;
    }
}