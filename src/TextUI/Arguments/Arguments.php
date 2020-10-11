<?php

namespace PhpApiDoc\TextUI\Arguments;

final class Arguments
{
    /**
     * @var bool
     */
    private $help = false;

    /**
     * @var bool
     */
    private $version = false;

    /**
     * @var null
     */
    private $configuration = null;

    /**
     * @var bool
     */
    private $generateConfiguration = false;

    /**
     * Arguments constructor.
     * @param bool $help
     * @param bool $version
     * @param null $configuration
     * @param bool $generateConfiguration
     */
    public function __construct($help, $version, $configuration, $generateConfiguration)
    {
        $this->help = $help;
        $this->version = $version;
        $this->configuration = $configuration;
        $this->generateConfiguration = $generateConfiguration;
    }

    /**
     * @return bool
     */
    public function isHelp()
    {
        return $this->help;
    }

    /**
     * @return bool
     */
    public function isVersion()
    {
        return $this->version;
    }

    /**
     * @return null
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @return bool
     */
    public function isGenerateConfiguration()
    {
        return $this->generateConfiguration;
    }
}