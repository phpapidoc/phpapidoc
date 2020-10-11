<?php

namespace PhpApiDoc\Output;

use PhpApiDoc\Framework\Api\ApiCollection;
use PhpApiDoc\Framework\Configuration\Configuration;

class AbstractOutput
{
    /**
     * @var ApiCollection
     */
    protected $apiCollection;

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * AbstractOutput constructor.
     * @param ApiCollection $apiCollection
     * @param Configuration $configuration
     */
    public function __construct($apiCollection, $configuration)
    {
        $this->apiCollection = $apiCollection;
        $this->configuration = $configuration;
    }

    protected function aa()
    {
        
    }
}