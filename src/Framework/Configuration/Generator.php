<?php

namespace PhpApiDoc\Framework\Configuration;

final class Generator
{
    const TEMPLATE = <<<EOT
<?xml version="1.0" encoding="UTF-8" ?>
<phpapidoc xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <apiPath>
        <directory>{apis_directory}</directory>
    </apiPath>
</phpapidoc>
EOT;

    public function generateDefaultConfiguration($apisDirectory)
    {
        return str_replace(
            [
                '{apis_directory}'
            ],
            [
                $apisDirectory
            ],
            self::TEMPLATE
        );
    }
}
