<?php

namespace PhpApiDoc\TextUI;

final class Help
{
    const LEFT_MARGIN = '  ';

    const HELP_TEXT = [
        'Usage' => [
            ['text' => 'phpapidoc [options]']
        ],
        'Options' => [
            ['arg' => '-h, --help', 'desc' => 'Display this usage information'],
            ['arg' => '-v, --version', 'desc' => 'Display the version and exits'],
            ['arg' => '-c, --config', 'desc' => 'Read configuration from XML file'],
            ['arg' => '-g, --generate', 'desc' => 'Generate configuration file with suggested settings'],
        ]
    ];

    private $maxArgLength = 0;

    public function __construct()
    {
        foreach (self::HELP_TEXT as $options) {
            foreach ($options as $option) {
                if (isset($option['arg'])) {
                    $this->maxArgLength = max($this->maxArgLength, strlen($option['arg']));
                }
            }
        }
    }


    public function writeToConsole()
    {
        $this->writePlaintext();
    }

    private function writePlaintext()
    {
        foreach (self::HELP_TEXT as $section => $options) {
            if ($section != 'Usage') {
                print PHP_EOL;
            }

            print "{$section}:" . PHP_EOL;

            foreach ($options as $option) {
                if (isset($option['text'])) {
                    print self::LEFT_MARGIN . $option['text'] . PHP_EOL;
                } elseif (isset($option['arg'])) {
                    $arg = str_pad($option['arg'], $this->maxArgLength);
                    print self::LEFT_MARGIN . $arg . ' ' . $option['desc'] . PHP_EOL;
                }
            }
        }
    }
}
