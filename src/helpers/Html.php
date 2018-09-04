<?php

namespace marvin255\serviform\helpers;

use InvalidArgumentException;

/**
 * Html helper.
 */
class Html
{
    /**
     * @param string $tag
     * @param array  $htmlOptions
     * @param string $content
     *
     * @return string
     */
    public static function createTag($tag, $htmlOptions = null, $content = null)
    {
        $return = '';
        $tag = self::clearAttributeKey(trim($tag));
        if ($tag) {
            $attributes = self::createAttributeString($htmlOptions);
            $return = '<' . $tag . (!empty($attributes) ? ' ' . $attributes : '');
            if ($content === false) {
                $return .= '>';
            } elseif ($content === true) {
                $return .= '>';
            } else {
                $return .= '>' . $content . self::createCloseTag($tag);
            }
        }

        return $return;
    }

    /**
     * @param string $tag
     *
     * @return string
     */
    public static function createCloseTag($tag)
    {
        return '</' . self::clearAttributeKey($tag) . '>';
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public static function createAttributeString($attributes)
    {
        $return = '';
        if (is_array($attributes)) {
            foreach ($attributes as $key => $value) {
                $key = self::clearAttributeKey($key);
                $value = self::clearAttributeValue($value);
                if ($key === '') {
                    continue;
                }
                $return .= " {$key}=\"{$value}\"";
            }
        }

        return trim($return);
    }

    /**
     * @param string $string
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public static function clearAttributeKey($string)
    {
        $replacer = '-';
        $clearString = preg_replace('/[^0-9a-z\-]{1}/i', $replacer, $string);
        if (preg_match('/^\\' . $replacer . '+$/', $clearString)) {
            throw new InvalidArgumentException('Can not convert to attribute key: ' . $string);
        }

        return strtolower(trim($clearString, $replacer));
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public static function clearAttributeValue($string)
    {
        return htmlspecialchars($string, ENT_QUOTES);
    }
}
