<?php

namespace PhpApiDoc\TextUI\Arguments;

final class ArgumentsBuilder
{
    const LONG_OPTIONS = [
        'version',
        'help',
        'config:',
        'generate'
    ];

    const SHORT_OPTIONS = 'c:gvh';

    public function build()
    {
        $options = getopt(self::SHORT_OPTIONS, self::LONG_OPTIONS);

        $configuration = null;
        $version = false;
        $help = false;
        $generate = false;

        foreach ($options as $key => $val) {
            switch ($key) {
                case 'config':
                case 'c':
                    $configuration = $val;
                    break;
                case 'generate':
                case 'g':
                    $generate = true;
                    break;
                case 'version':
                case 'v':
                    $version = true;
                    break;
                case 'help':
                case 'h':
                    $help = true;
                    break;
            }
        }

        return new Arguments(
            $help,
            $version,
            $configuration,
            $generate
        );
    }
}