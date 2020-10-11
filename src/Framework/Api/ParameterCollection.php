<?php

namespace PhpApiDoc\Framework\Api;

class ParameterCollection
{
    /**
     * @var Parameter[]
     */
    private $parameters = [];

    /**
     * ParameterCollection constructor.
     * @param Parameter[] $parameters
     */
    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    public function get()
    {
        return $this->parameters;
    }

    /**
     * @param ParameterCollection $parameterCollection
     */
    public function merge($parameterCollection)
    {
        $this->parameters = array_merge($parameterCollection->get(), $this->parameters);
    }

    public function makeParametersTree()
    {
        $parameters = $this->parameters;
        krsort($parameters);

        foreach ($parameters as $k => $parameter) {
            $parameter->sortChildren();
            $parameter->fixSample();
            foreach ($parameters as $kp => $parent) {
                if ($parameter->setParent($parent)) {
                    $parent->addChild($parameter);
                    unset($parameters[$k]);
                    $parameters[$kp] = $parent;
                }
            }
        }

        ksort($parameters);

        $this->parameters = $parameters;
    }

    public function toArray()
    {
        $data = [];
        foreach ($this->parameters as $parameter) {
            $data[] = $parameter->toArray();
        }

        return $data;
    }

    public function toSample()
    {
        $data = [];
        foreach ($this->parameters as $parameter) {
            $data[$parameter->getField()] = $parameter->toSample();
        }

        return $data;
    }
}
