<?php

namespace PhpApiDoc\Framework\Api;

class Api
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $author;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $group;

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $url;

    /**
     * @var array
     */
    private $tags = [];

    /**
     * @var array
     */
    private $uses = [];

    /**
     * @var ParameterCollection
     */
    private $headerCollection;

    /**
     * @var ParameterCollection
     */
    private $paramCollection;

    /**
     * @var ParameterCollection
     */
    private $successResponseCollection;

    /**
     * @var ParameterCollection
     */
    private $errorResponseCollection;

    /**
     * Api constructor.
     * @param string $name
     * @param string $title
     * @param string $description
     * @param string $author
     * @param string $version
     * @param string $group
     * @param string $method
     * @param string $url
     * @param array $tags
     * @param array $uses
     * @param ParameterCollection $headerCollection
     * @param ParameterCollection $paramCollection
     * @param ParameterCollection $successResponseCollection
     * @param ParameterCollection $errorResponseCollection
     */
    public function __construct(
        $name,
        $title,
        $description,
        $author,
        $version,
        $group,
        $method,
        $url,
        $tags,
        $uses,
        $headerCollection,
        $paramCollection,
        $successResponseCollection,
        $errorResponseCollection
    )
    {
        $this->name = $name;
        $this->title = $title;
        $this->description = $description;
        $this->author = $author;
        $this->version = $version;
        $this->group = $group;
        $this->method = $method;
        $this->url = $url;
        $this->tags = $tags;
        $this->uses = $uses;
        $this->headerCollection = $headerCollection;
        $this->paramCollection = $paramCollection;
        $this->successResponseCollection = $successResponseCollection;
        $this->errorResponseCollection = $errorResponseCollection;

        $this->setKey();
    }

    private function setKey()
    {
        $this->key = md5($this->name . $this->version);
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function toArray()
    {
        return [
            'key' => $this->key,
            'name' => $this->name,
            'title' => $this->title,
            'description' => $this->description,
            'author' => $this->author,
            'version' => $this->version,
            'group' => $this->group,
            'method' => $this->method,
            'url' => $this->url,
            'tags' => $this->tags,
            'headers' => $this->headerCollection->toArray(),
            'params' => $this->paramCollection->toArray(),
            'success_response_sample' => $this->successResponseCollection->toSample(),
            'success_response' => $this->successResponseCollection->toArray(),
            'error_response_sample' => $this->errorResponseCollection->toSample(),
            'error_response' => $this->errorResponseCollection->toArray(),
        ];
    }

    /**
     * @param $apiDefineCollection
     */
    public function matchUse($apiDefineCollection)
    {
        foreach ($this->uses as $use) {
            $apiDefine = $apiDefineCollection->get($use);
            $this->headerCollection->merge($apiDefine->getHeaderCollection());
            $this->paramCollection->merge($apiDefine->getparamCollection());
            $this->successResponseCollection->merge($apiDefine->getSuccessResponseCollection());
            $this->errorResponseCollection->merge($apiDefine->getErrorResponseCollection());
            $this->tags = array_merge($apiDefine->getTags(), $this->tags);
        }
    }

    public function makeParametersTree()
    {
        $this->headerCollection->makeParametersTree();
        $this->paramCollection->makeParametersTree();
        $this->successResponseCollection->makeParametersTree();
        $this->errorResponseCollection->makeParametersTree();
    }
}
