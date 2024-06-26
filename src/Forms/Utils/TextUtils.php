<?php

namespace Bolero\Forms\Utils;

class TextUtils
{
    public static function slugify(string $text): ?string
    {
        // replace non letter or digits by -
        $text = preg_replace('#[^\\pL\d]+#u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('#[^-\w]+#', '', $text);

        if (empty($text)) {
            return null;
        }

        $text = iconv('us-ascii', 'utf-8', $text);

        return $text;
    }

    public static function format(string|array|object $string, ...$params): string
    {
        $result = $string;

        if (is_object($string)) {
            $result = json_encode($string, JSON_PRETTY_PRINT);
        }
        if (is_array($string)) {
            $result = print_r($string, true);
        }
        if (count($params) > 0 && count($params[0]) > 0) {
            $result = vsprintf($string, $params[0]);
        }

        return $result;
    }

    public static function jsonToPhpArray(string $json): string
    {
        $result = '<?php' . PHP_EOL;
        $result .= 'return [' . PHP_EOL;

        $l = mb_strlen($json, 'UTF-8');
        $text = mb_substr($json, 1, $l - 2);

        $text = mb_ereg_replace(': ', ' => ', $text);
        $text = mb_ereg_replace('{', '[', $text);
        $text = mb_ereg_replace('}', ']', $text);
        $text = mb_ereg_replace('\\\/', '/', $text);
        $text = "\t" . trim($text);


        $result .= $text . PHP_EOL;

        $result .= '];' . PHP_EOL;

        return $result;
    }
}
