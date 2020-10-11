<?php

namespace PhpApiDoc\Framework\Api;

class ApiCollection
{
    private $apis = [];

    /**
     * ApiCollection constructor.
     * @param Api[] $apis
     */
    public function __construct($apis = [])
    {
        $this->apis = $apis;
    }

    /**
     * @param Api $api
     */
    public function add($api)
    {
        $this->apis[$api->getKey()] = $api;
    }

    /**
     * @param ApiDefineCollection $apiDefineCollection
     */
    public function buildApi($apiDefineCollection)
    {
        $this->apis = array_map(function ($api) use ($apiDefineCollection) {
            $api->matchUse($apiDefineCollection);
            $api->makeParametersTree();
            return $api;
        }, $this->apis);
    }

    public function toArray()
    {
        $data = [];
        foreach ($this->apis as $api) {
            $data[] = $api->toArray();
        }

        return $data;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->apis);
    }
}
