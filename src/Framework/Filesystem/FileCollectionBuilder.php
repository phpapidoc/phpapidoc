<?php

namespace PhpApiDoc\Framework\Filesystem;

use PhpApiDoc\Framework\Exception\InvalidPathException;
use PhpApiDoc\Support\Str;

final class FileCollectionBuilder
{
    private $except_dirs = ['.', '..'];

    private $files = [];

    public function fromApiPath($apiPath)
    {
        $this->files = [];
        $exclude = array_map(function ($file) {
            return $file->getPath();
        }, $apiPath->getExclude()->asArray());

        $directories = $apiPath->getDirectories()->asArray();
        foreach ($directories as $directory) {
            $this->recursiveDirectory($directory->getPath(), $directory->getPrefix(), $directory->getSuffix());
        }
        $files = array_map(function ($file) {
            return $file->getPath();
        }, $apiPath->getFiles()->asArray());

        $files = array_merge($files, $this->files);
        $files = array_unique($files);
        $files = array_diff($files, $exclude);

        $files = array_map(function ($file) {
            return new File($file);
        }, $files);

        return FileCollection::fromArray($files);
    }

    private function recursiveDirectory($path, $prefix = '', $suffix = '')
    {
        if ($this->isValidFile($path, $prefix, $suffix)) {
            $this->files[] = $path;
        } elseif (is_dir($path)) {
            $list = scandir($path);
            foreach ($list as $item) {
                if (!in_array($item, $this->except_dirs)) {
                    $this->recursiveDirectory($path . DIRECTORY_SEPARATOR . $item);
                }
            }
        } elseif (!file_exists($path)) {
            throw new InvalidPathException(sprintf("Invalid path : %s", $path));
        }
    }

    private function isValidFile($path, $prefix, $suffix)
    {
        $prefix_valid = true;
        if (!empty($prefix)) {
            $prefix_valid = Str::startsWith(basename($path), $prefix);
        }

        $suffix_valid = true;
        if (!empty($suffix)) {
            $suffix_valid = Str::endsWith($path, $suffix);
        }
        return is_file($path) && $prefix_valid && $suffix_valid;
    }

}
