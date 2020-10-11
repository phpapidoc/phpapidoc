<?php

namespace PhpApiDoc\Output;

class HtmlOutput extends AbstractOutput
{
    const API_JSON = 'phpapidoc.json.js';

    const CONFIG_JSON = 'phpapidoc.config.json.js';

    public function store()
    {
        if ($this->isCopyTemplate()) {
            $this->copyTemplate();
        }

        file_put_contents(
            $this->configuration->getOutput() . DIRECTORY_SEPARATOR . self::API_JSON,
            'window.apiList=' . json_encode($this->apiCollection->toArray(), JSON_UNESCAPED_UNICODE)
        );

        file_put_contents(
            $this->configuration->getOutput() . DIRECTORY_SEPARATOR . self::CONFIG_JSON,
            'window.apiConfig=' . json_encode($this->configuration->toArray(), JSON_UNESCAPED_UNICODE)
        );
    }

    private function copyTemplate()
    {
        $this->recurseCopy(dirname($this->configuration->getTemplate()), $this->configuration->getOutput());
    }

    /**
     * @return bool
     */
    private function isCopyTemplate()
    {
        $name = $this->configuration->getOutput() . DIRECTORY_SEPARATOR . basename($this->configuration->getTemplate());
        return !file_exists($name);
    }

    /**
     * @param string $source
     * @param string $dest
     */
    protected function recurseCopy($source, $dest)
    {
        $handle = opendir($source);
        if (!is_dir($dest)) {
            mkdir($dest, 0755, true);
        }

        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                if (is_dir($source . DIRECTORY_SEPARATOR . $file)) {
                    $this->recurseCopy($source . DIRECTORY_SEPARATOR . $file, $dest . DIRECTORY_SEPARATOR . $file);
                } else {
                    copy($source . DIRECTORY_SEPARATOR . $file, $dest . DIRECTORY_SEPARATOR . $file);
                }
            }
        }

        closedir($handle);
    }
}