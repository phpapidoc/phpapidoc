<?php

namespace PhpApiDoc\TextUI;

use PhpApiDoc\Framework\ApiDocHandler;
use PhpApiDoc\Framework\Configuration\Generator;
use PhpApiDoc\Framework\Configuration\Loader;
use PhpApiDoc\TextUI\Arguments\ArgumentsBuilder;

class Command
{
    private $versionStringPrinted = false;

    public static function main($exit = true)
    {
        return (new static())->run($exit);
    }

    public function run($exit = true)
    {
        try {
            $start = microtime(true);
            $arguments = $this->handleArguments();

            $configuration = $this->loadConfiguration($arguments->getConfiguration());

            $handler = new ApiDocHandler($configuration);
            $handler->handle();

            $this->printVersionString();

            print "配置文件：" . $configuration->getFilename() . PHP_EOL;
            print "API文件数量：" . $handler->getFileCount() . PHP_EOL;
            print "API数量：" . $handler->getApiCount() . PHP_EOL;
//        print "保存结果为 HTML:" . $handler->isHtml() . " JSON:" . $handler->isJson() . PHP_EOL;
//        print "output:" . PHP_EOL;

            return 1;
        } catch (\Exception $e) {
            print $e->getMessage() . PHP_EOL;
        }
    }

    public function handleArguments()
    {
        $arguments = (new ArgumentsBuilder())->build();

        if ($arguments->isGenerateConfiguration()) {
            print 'Generating phpapidoc.xml in ' . getcwd() . PHP_EOL . PHP_EOL;

            print 'Apis directory (relative to path shows above; default:apis)';

            $apiDirectory = trim(fgets(STDIN));

            if ($apiDirectory == '') {
                $apiDirectory = 'apis';
            }

            $generator = new Generator();

            file_put_contents('phpapidoc.xml', $generator->generateDefaultConfiguration($apiDirectory));

            print PHP_EOL . 'Generated phpapidoc.xml in ' . getcwd() . PHP_EOL;

            exit(1);
        }

        if ($arguments->isVersion()) {
            $this->printVersionString();

            exit(1);
        }

        if ($arguments->isHelp()) {
            $this->showHelp();

            exit(1);
        }

        return $arguments;
    }

    private function printVersionString()
    {
        if ($this->versionStringPrinted) {
            return;
        }

        print Version::getVersionString() . PHP_EOL . PHP_EOL;

        $this->versionStringPrinted = true;
    }

    private function showHelp()
    {
        $this->printVersionString();

        (new Help())->writeToConsole();
    }

    private function loadConfiguration($filename)
    {
        if (empty($filename)) {
            $filename = getcwd() . DIRECTORY_SEPARATOR . 'phpapidoc.xml';
        }
        return (new Loader())->load($filename);
    }
}