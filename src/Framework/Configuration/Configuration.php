<?php

namespace PhpApiDoc\Framework\Configuration;

final class Configuration
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $name;

    /**
     * @var ApiPath
     */
    private $apiPath;

    /**
     * @var string
     */
    private $output;

    /**
     * @var bool
     */
    private $json = true;

    /**
     * @var bool
     */
    private $html = true;

    /**
     * @var string
     */
    private $template = __DIR__ . '/../../../template/index.html';

    /**
     * @var array
     */
    private $urls = [];

    private $header_fields = [];

    private $param_fields = [];

    private $response_fields = [];

    /**
     * Configuration constructor.
     * @param string $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param ApiPath $apiPath
     */
    public function setApiPath($apiPath)
    {
        $this->apiPath = $apiPath;
    }

    /**
     * @param string $output
     */
    public function setOutput($output)
    {
        $this->output = $output;
    }

    /**
     * @param bool $json
     */
    public function setJson($json)
    {
        $this->json = $json;
    }

    /**
     * @param bool $html
     */
    public function setHtml($html)
    {
        $this->html = $html;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @param array $urls
     */
    public function setUrls($urls)
    {
        $this->urls = $urls;
    }

    /**
     * @param array $header_fields
     */
    public function setHeaderFields($header_fields)
    {
        $this->header_fields = $header_fields;
    }

    /**
     * @param array $param_fields
     */
    public function setParamFields($param_fields)
    {
        $this->param_fields = $param_fields;
    }

    /**
     * @param array $response_fields
     */
    public function setResponseFields($response_fields)
    {
        $this->response_fields = $response_fields;
    }

    /**
     * @return ApiPath
     */
    public function getApiPath()
    {
        return $this->apiPath;
    }

    /**
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @return bool
     */
    public function isJson()
    {
        return $this->json;
    }

    /**
     * @return bool
     */
    public function isHtml()
    {
        return $this->html;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'urls' => $this->urls,
            'headers' => $this->header_fields,
            'params' => $this->param_fields,
            'responses' => $this->response_fields,
        ];
    }
}