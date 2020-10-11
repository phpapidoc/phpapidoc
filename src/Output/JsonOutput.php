<?php

namespace PhpApiDoc\Output;

class JsonOutput extends AbstractOutput
{
    const API_JSON = 'phpapidoc.json';

    const CONFIG_JSON = 'phpapidoc.config.json';

    public function store()
    {
        file_put_contents(
            $this->configuration->getOutput() . DIRECTORY_SEPARATOR . self::API_JSON,
            json_encode($this->apiCollection->toArray(), JSON_UNESCAPED_UNICODE)
        );

        file_put_contents(
            $this->configuration->getOutput() . DIRECTORY_SEPARATOR . self::CONFIG_JSON,
            json_encode($this->configuration->toArray(), JSON_UNESCAPED_UNICODE)
        );
    }
}