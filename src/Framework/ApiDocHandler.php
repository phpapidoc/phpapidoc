<?php

namespace PhpApiDoc\Framework;

use PhpApiDoc\Framework\Api\Api;
use PhpApiDoc\Framework\Api\ApiCollection;
use PhpApiDoc\Framework\Api\ApiDefine;
use PhpApiDoc\Framework\Api\ApiDefineCollection;
use PhpApiDoc\Framework\BlockDoc\BlockDocMatcher;
use PhpApiDoc\Framework\BlockDoc\BlockDocParser;
use PhpApiDoc\Framework\Configuration\Configuration;
use PhpApiDoc\Framework\Filesystem\FileCollectionBuilder;
use PhpApiDoc\Output\HtmlOutput;
use PhpApiDoc\Output\JsonOutput;

class ApiDocHandler
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var ApiCollection
     */
    private $apiCollection;

    /**
     * @var ApiDefineCollection
     */
    private $apiDefineCollection;

    private $fileCount = 0;

    private $apiCount = 0;

    private $json = false;

    private $html = false;

    /**
     * ApiDocHandler constructor.
     * @param Configuration $configuration
     */
    public function __construct($configuration)
    {
        $this->configuration = $configuration;

        $this->apiCollection = new ApiCollection();
        $this->apiDefineCollection = new ApiDefineCollection();
    }

    public function handle()
    {
        $files = (new FileCollectionBuilder())->fromApiPath($this->configuration->getApiPath());
        $this->parse($files);
        $this->apiCollection->buildApi($this->apiDefineCollection);
        $this->fileCount = count($files);
        $this->apiCount = $this->apiCollection->count();

        if ($this->configuration->isJson()) {
            (new JsonOutput($this->apiCollection, $this->configuration))->store();
            $this->json = true;
        }

        if ($this->configuration->isHtml()) {
            (new HtmlOutput($this->apiCollection, $this->configuration))->store();
            $this->html = true;
        }
    }

    private function parse($files)
    {
        $matcher = new BlockDocMatcher();
        $parser = new BlockDocParser();

        foreach ($files as $file) {
            foreach ($matcher->matchBlockDocs($file->getContents()) as $doc) {
                $result = $parser->parse($doc);

                if ($result instanceof Api) {
                    $this->apiCollection->add($result);
                } elseif ($result instanceof ApiDefine) {
                    $this->apiDefineCollection->add($result);
                }
            }
        }
    }

    /**
     * @return int
     */
    public function getFileCount()
    {
        return $this->fileCount;
    }

    /**
     * @return int
     */
    public function getApiCount()
    {
        return $this->apiCount;
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
     * @return Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @return ApiCollection
     */
    public function getApiCollection()
    {
        return $this->apiCollection;
    }
}