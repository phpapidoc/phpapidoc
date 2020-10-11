<?php

namespace PhpApiDoc\Framework\Api;

class ApiDefineCollection
{
    private $defines = [];

    /**
     * ApiDefineCollection constructor.
     * @param ApiDefine[] $defines
     */
    public function __construct($defines = [])
    {
        $this->defines = $defines;
    }

    /**
     * @param ApiDefine $apiDefine
     */
    public function add($apiDefine)
    {
        $this->defines[$apiDefine->getKey()] = $apiDefine;
    }

    /**
     * @param $key
     * @return ApiDefine
     * @throws \Exception
     */
    public function get($key)
    {
        if (!isset($this->defines[$key])) {
            throw new \Exception('ApiDefine not defined');
        }

        return $this->defines[$key];
    }
}
