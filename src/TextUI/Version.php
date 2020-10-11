<?php

namespace PhpApiDoc\TextUI;

final class Version
{
    const VERSION = 'dev';

    public static function getVersion()
    {
        return self::VERSION;
    }

    public static function getVersionString()
    {
        return 'PHPApiDoc ' . self::getVersion();
    }
}
