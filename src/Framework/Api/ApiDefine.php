<?php

namespace PhpApiDoc\Framework\Api;

final class ApiDefine
{
    /**
     * @var string
     */
    private $name;

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
     * @var array
     */
    private $tags = [];

    /**
     * ApiDefine constructor.
     * @param string $name
     * @param ParameterCollection $headerCollection
     * @param ParameterCollection $paramCollection
     * @param ParameterCollection $successResponseCollection
     * @param ParameterCollection $errorResponseCollection
     * @param array $tags
     */
    public function __construct(
        $name,
        $headerCollection,
        $paramCollection,
        $successResponseCollection,
        $errorResponseCollection,
        array $tags
    )
    {
        $this->name = $name;
        $this->headerCollection = $headerCollection;
        $this->paramCollection = $paramCollection;
        $this->successResponseCollection = $successResponseCollection;
        $this->errorResponseCollection = $errorResponseCollection;
        $this->tags = $tags;
    }

    public function getKey()
    {
        return $this->name;
    }

    /**
     * @return ParameterCollection
     */
    public function getHeaderCollection()
    {
        return $this->headerCollection;
    }

    /**
     * @return ParameterCollection
     */
    public function getParamCollection()
    {
        return $this->paramCollection;
    }

    /**
     * @return ParameterCollection
     */
    public function getSuccessResponseCollection()
    {
        return $this->successResponseCollection;
    }

    /**
     * @return ParameterCollection
     */
    public function getErrorResponseCollection()
    {
        return $this->errorResponseCollection;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }
}
