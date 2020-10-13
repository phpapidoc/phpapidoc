<?php

namespace PhpApiDoc\Framework\Configuration;

use \DOMDocument;
use PhpApiDoc\Framework\Api\Parameter;
use PhpApiDoc\Framework\Api\ParameterCollection;
use PhpApiDoc\Framework\Filesystem\Directory;
use PhpApiDoc\Framework\Filesystem\DirectoryCollection;
use PhpApiDoc\Framework\Filesystem\File;
use PhpApiDoc\Framework\Filesystem\FileCollection;

final class Loader
{
    /**
     * @var Configuration
     */
    private $configuration;

    public function load($filename)
    {
        $filename = $this->toAbsolutePath(getcwd(), $filename);

        if (!file_exists($filename)) {
            throw new \Exception('Config file does not exist: ' . $filename);
        }

        $document = new DOMDocument();
        $document->load($filename);
        $xpath = new \DOMXPath($document);

        // TODO:: validate
        $this->configuration = new Configuration($filename);
        $this->base($filename, $xpath);
        $this->urls($xpath);
        $this->fields($xpath);
        $this->apiPath($filename, $xpath);

        return $this->configuration;
    }

    private function validate($document)
    {
        $original = libxml_use_internal_errors(true);

        $xsdFilename = __DIR__ . '/../../../phpapidoc.xsd';

        $document->schemaValidate($xsdFilename);

        $tmp = libxml_get_errors();
        libxml_clear_errors();
        libxml_use_internal_errors($original);

        $errors = [];
        foreach ($tmp as $error) {
            if (!isset($errors[$error->line])) {
                $errors[$error->line] = [];
            }

            $errors[$error->line][] = trim($error->message);
        }

        return $errors;
    }

    private function base($filename, $xpath)
    {
        foreach ($xpath->query('/phpapidoc') as $element) {
            $name = 'PHP API DOC';
            if ($element->hasAttribute('name')) {
                $name = $element->getAttribute('name');
            }
            $this->configuration->setName($name);

            if ($element->hasAttribute('html')) {
                $this->configuration->setHtml($element->getAttribute('html') == 'true');
            }

            if ($element->hasAttribute('json')) {
                $this->configuration->setJson($element->getAttribute('json') == 'true');
            }

            if ($element->hasAttribute('output')) {
                $output = $this->toAbsolutePath($filename, $element->getAttribute('output'));

                if (!is_dir($output)) {
                    throw new \Exception(sprintf(
                        'Directory %s is not a valid path.',
                        $output
                    ));
                }

                $this->configuration->setOutput($output);
            }

            if ($element->hasAttribute('template')) {
                $template = $this->toAbsolutePath($filename, $element->getAttribute('template'));

                if (!is_file($template)) {
                    throw new \Exception(sprintf(
                        'File %s is not a valid file.',
                        $template
                    ));
                }

                $this->configuration->setTemplate($template);
            }
        }
    }

    private function apiPath($filename, $xpath)
    {
        $directories = [];
        $files = [];
        $exclude = [];
        foreach ($xpath->query('/phpapidoc/apipath') as $element) {
            foreach ($element->getElementsByTagName('directory') as $node) {
                $path = $this->toAbsolutePath($filename, $node->nodeValue);
                if (!is_dir($path)) {
                    throw new \Exception(sprintf(
                        'Directory %s is not a valid path.',
                        $path
                    ));
                }

                $prefix = '';
                if ($node->hasAttribute('prefix')) {
                    $prefix = $node->getAttribute('prefix');
                }
                $suffix = 'php';
                if ($node->hasAttribute('suffix')) {
                    $suffix = $node->getAttribute('suffix');
                }
                $directories[] = new Directory($path, $prefix, $suffix);
            }

            foreach ($element->getElementsByTagName('file') as $node) {
                $path = $this->toAbsolutePath($filename, $node->nodeValue);

                if (!is_file($path)) {
                    throw new \Exception(sprintf(
                        'File %s is not a valid file.',
                        $path
                    ));
                }
                $files[] = new File($path);
            }

            foreach ($element->getElementsByTagName('exclude') as $node) {
                $path = $this->toAbsolutePath($filename, $node->nodeValue);
                if (!is_file($path)) {
                    throw new \Exception(sprintf(
                        'Exclude %s is not a valid file.',
                        $path
                    ));
                }
                $exclude[] = new File($path);
            }
        }

        $this->configuration->setApiPath(
            new ApiPath(
                DirectoryCollection::fromArray($directories),
                FileCollection::fromArray($files),
                FileCollection::fromArray($exclude)
            )
        );
    }

    private function urls($xpath)
    {
        $urls = [];
        foreach ($xpath->query('/phpapidoc/urls/url') as $element) {
            $urls[] = [
                'name' => $element->getAttribute('name'),
                'url' => $element->nodeValue
            ];
        }

        $this->configuration->setUrls($urls);
    }

    private function fields($xpath)
    {
        $headers = [];
        $params = [];
        $responses = [];

        foreach ($xpath->query('/phpapidoc/fields') as $element) {

            foreach ($element->getElementsByTagName('header') as $node) {
                $headers[] = new Parameter(
                    $node->nodeValue,
                    $node->getAttribute('type'),
                    $node->getAttribute('description'),
                    $node->getAttribute('sample'),
                    $node->getAttribute('required') == 'true'
                );
            }

            foreach ($element->getElementsByTagName('param') as $node) {
                $params[] = new Parameter(
                    $node->nodeValue,
                    $node->getAttribute('type'),
                    $node->getAttribute('description'),
                    $node->getAttribute('sample'),
                    $node->getAttribute('required') == 'true'
                );
            }

            foreach ($element->getElementsByTagName('response') as $node) {
                $responses[] = new Parameter(
                    $node->nodeValue,
                    $node->getAttribute('type'),
                    $node->getAttribute('description'),
                    $node->getAttribute('sample'),
                    false
                );
            }
        }

        $this->configuration->setHeaderFields((new ParameterCollection($headers))->toArray());
        $this->configuration->setParamFields((new ParameterCollection($params))->toArray());
        $this->configuration->setResponseFields((new ParameterCollection($responses))->toArray());
    }

    private function toAbsolutePath($filename, $path)
    {
        $path = trim($path);

        if (strpos($path, '/') === 0) {
            return $path;
        }

        // Matches the following on Windows:
        //  - \\NetworkComputer\Path
        //  - \\.\D:
        //  - \\.\c:
        //  - C:\Windows
        //  - C:\windows
        //  - C:/windows
        //  - c:/windows
        if (
            \defined('PHP_WINDOWS_VERSION_BUILD') &&
            ($path[0] === '\\' || (\strlen($path) >= 3 &&
                    \preg_match('#^[A-Z]\:[/\\\]#i', \substr($path, 0, 3))))
        ) {
            return $path;
        }

        if (strpos($path, '://') !== false) {
            return $path;
        }

        $file = dirname($filename) . DIRECTORY_SEPARATOR . $path;

        if (!file_exists($file)) {
            if ($includePathFile = stream_resolve_include_path($path)) {
                $file = $includePathFile;
            }
        }

        return $file;
    }
}
