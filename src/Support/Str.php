<?php

namespace PhpApiDoc\Support;

class Str
{
    /**
     * Determine if a given string ends with a given substring.
     *
     * @param $haystack
     * @param $needles
     * @return bool
     */
    public static function endsWith($haystack, $needles)
    {
        foreach ((array)$needles as $needle) {
            if (substr($haystack, -strlen($needles)) === (string)$needle) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if a given string starts with a given substring.
     *
     * @param $haystack
     * @param $needles
     * @return bool
     */
    public static function startsWith($haystack, $needles)
    {
        foreach ((array)$needles as $needle) {
            if ($needle !== '' && substr($haystack, 0, strlen($needle)) === (string)$needle) {
                return true;
            }
        }

        return false;
    }

    /**
     * Replace multiple spaces in a given string with a single space.
     *
     * @param $subject
     * @return string|string[]|null
     */
    public static function replaceSpace($subject)
    {
        return preg_replace('/\s+/', ' ', $subject);
    }

    /**
     * @param $str
     * @return false|string[]
     */
    public static function explode($str)
    {
        return explode(' ', self::replaceSpace(trim($str)));
    }

    public static function explodeBraces($str)
    {
        $left = '{';
        $right = '}';
        $arr = self::explode($str);
        $result = [];
        $tmp = '';

        foreach ($arr as $item) {
            $is_start = self::startsWith($item, $left);
            $is_end = self::endsWith($item, $right);
            if (!empty($tmp)) {
                $tmp .= ' ' . $item;
                if ($is_end) {
                    // 去掉首尾各一个{ }
                    $result[] = trim(substr($tmp, 1, strlen($tmp) - 2));
                }
            } else {
                if ($is_start && $is_end) {
                    $result[] = trim(substr($item, 1, strlen($item) - 2));
                } elseif ($is_start) {
                    $tmp = $item;
                } else {
                    $result[] = $item;
                }
            }
        }

        if (!empty($tmp)) {
            $result[] = $tmp;
        }

        return $result;
    }

    public static function explodeBracesSkip($str, $skip = 0)
    {
        $result = [];
        $arr = self::explode($str);

        $i = 0;
        while ($i < $skip && count($arr) > 0) {
            $result[] = array_shift($arr);

            $i++;
        }

        if (count($arr) > 0) {
            $str = join(' ', $arr);
            $result = array_merge($result, self::explodeBraces($str));
        }

        return $result;
    }

}