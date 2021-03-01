<?php

namespace PhpApiDoc\Framework\Api;

use PhpApiDoc\Support\Str;

class Parameter
{
    const DELIMITER = '.';
    const SUFFIX_OBJECT = '{}';
    const SUFFIX_ARRAY = '[]';

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $type;

    /**
     * @var bool
     */
    private $type_object = false;

    /**
     * @var bool
     */
    private $type_array = false;

    /**
     * @var string
     */
    private $description;

    /**
     * @var mixed
     */
    private $sample;

    /**
     * @var bool
     */
    private $required = true;

    private $dotCount = 0;

    /**
     * @var array
     */
    private $children = [];

    /**
     * Parameter constructor.
     * @param string $field
     * @param string $type
     * @param string $description
     * @param mixed $sample
     * @param bool $required
     */
    public function __construct($field, $type, $description, $sample, $required = true)
    {
        $this->setKey($field);
        $this->setType($type);
        $this->description = $description;
        $this->sample = $sample;
        $this->required = $required;
    }

    private function setKey($key)
    {
        if (Str::startsWith($key, '$')) {
            $key = substr($key, 1);
        }

        $this->key = $key;
        $this->field = $key;
        $this->dotCount = substr_count($key, self::DELIMITER);
    }

    private function setType($type)
    {
        if (Str::endsWith($type, self::SUFFIX_OBJECT)) {
            $this->type_object = true;
        } elseif (Str::endsWith($type, self::SUFFIX_ARRAY)) {
            $this->type_array = true;
        }

        $this->type = $type;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getField()
    {
        return $this->field;
    }

    public function getDotCount()
    {
        return $this->dotCount;
    }

    /**
     * @return bool
     */
    public function isTypeObject()
    {
        return $this->type_object;
    }

    /**
     * @return bool
     */
    public function isTypeArray()
    {
        return $this->type_array;
    }

    /**
     * @param Parameter $parent
     * @return bool
     */
    public function isParent($parent)
    {
        if (($parent->isTypeArray() || $parent->isTypeObject()) &&
            0 === strpos($this->getKey(), $parent->getKey())
        ) {
            $field = substr($this->getKey(), strlen($parent->getKey()));
            return strlen($field) > 1 && Str::startsWith($field, self::DELIMITER) &&
                substr_count($field, self::DELIMITER) == 1;
        }
        return false;
    }

    /**
     * @param Parameter $parent
     */
    public function setParent($parent)
    {
        if ($this->isParent($parent)) {
            $this->field = substr($this->getKey(), strlen($parent->getKey()) + 1);
        }
    }

    /**
     * @param Parameter $parameter
     */
    public function addChild($parameter)
    {
        $this->children[$parameter->getKey()] = $parameter;
    }

    public function sortChildren()
    {
        ksort($this->children);
    }

    /**
     * TODO:: 使用配置文件处理
     */
    public function fixSample()
    {
        $sample = $this->sample;
        if ($this->isTypeArray() && count($this->children) == 0) {
            $type = substr($this->type, 0, strlen($this->type) - 2);
            $sample = Str::explode($sample);
            $sample = array_map(function ($item) use ($type) {
                return $this->fixSampleType($type, $item);
            }, $sample);
        } else {
            $sample = $this->fixSampleType($this->type, $sample);
        }

        $this->sample = $sample;
    }

    private function fixSampleType($type, $sample)
    {
        $type = strtolower($type);
        switch ($type) {
            case 'int':
            case 'integer':
                $sample = intval($sample);
                break;
            case 'float':
                $sample = floatval($sample);
                break;
            case 'bool':
            case 'boolean':
                $sample = boolval($sample);
                break;
            case 'string':
                if ($sample == 'null') {
                    $sample = null;
                }
                break;
        }

        return $sample;
    }

    public function toArray($level = 0)
    {
        $children = [];
        foreach ($this->children as $child) {
            $children[] = $child->toArray($level + 1);
        }

        $sample = $this->sample;
        if (!$children && ($this->type_array || $this->type_object)) {
            $sample = join(',', $sample);
        }
        return [
            'key' => $this->key,
            'field' => $this->field,
            'type' => $this->type,
            'description' => $this->description,
            'sample' => $sample,
            'required' => $this->required,
            'children' => $children,
            'level' => $level,
        ];
    }

    public function toSample()
    {
        $children = [];
        foreach ($this->children as $child) {
            $children[$child->getField()] = $child->toSample();
        }

        if ($this->type_array && $children) {
            return [$children];
        } elseif ($this->type_object) {
            return $children;
        } else {
            return $this->sample;
        }
    }
}
