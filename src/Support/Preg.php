<?php

namespace PhpApiDoc\Support;

use PhpApiDoc\Framework\Exception\InvalidFieldException;

class Preg
{
    /**
     * @param string $str
     * @param string $pattern
     * @return string
     */
    public static function str($str, $pattern)
    {
        if (preg_match($pattern, $str, $matches)) {
            return isset($matches[1]) ? $matches[1] : '';
        }

        return '';
    }

    /**
     * @param string $str
     * @param string $pattern
     * @return string
     */
    public static function firstStr($str, $pattern)
    {
        if (preg_match($pattern, $str, $matches)) {
            $arr = Str::explodeBraces($matches[1]);

            return isset($arr[0]) ? $arr[0] : '';
        }

        return '';
    }

    /**
     * @param string $str
     * @param string $pattern
     * @return array
     */
    public static function arr($str, $pattern)
    {
        if (preg_match($pattern, $str, $matches)) {
            return Str::explodeBraces(isset($matches[1]) ? $matches[1] : '');
        }

        return [];
    }

    /**
     * @param $doc
     * @param $pattern
     * @return array
     * @throws InvalidFieldException
     */
    public static function parameters($doc, $pattern)
    {
        $result = [];
        if (preg_match_all($pattern, $doc, $matches)) {
            foreach ($matches[0] as $match) {
                $field = self::parameter($match);
                $result[$field['field']] = $field;
            }
        }

        return $result;
    }

    /**
     * @param $line_doc
     * @return array
     * @throws InvalidFieldException
     */
    public static function parameter($line_doc)
    {
        $arr = Str::explodeBracesSkip($line_doc, 3);

        if (count($arr) < 4) {
            throw new InvalidFieldException('Field annotation must more than 4 parts. Annotation: ' . $line_doc);
        }

        return [
            'required' => false !== stripos($arr[0], 'null'),
            'type' => $arr[1],
            'field' => $arr[2],
            'description' => $arr[3],
            'sample' => isset($arr[4]) ? $arr[4] : '',
        ];
    }
}
