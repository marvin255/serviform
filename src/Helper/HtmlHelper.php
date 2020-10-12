<?php

declare(strict_types=1);

namespace Marvin255\Serviform\Helper;

use InvalidArgumentException;

/**
 * Helper that can create diffrent html tags.
 */
class HtmlHelper
{
    /**
     * Creates string for html tag.
     *
     * @param string                $name
     * @param string                $content
     * @param array<string, string> $attributes
     *
     * @return string
     */
    public static function tag(string $name, string $content = '', array $attributes = []): string
    {
        $startTag = self::startTag($name, $attributes);
        $endTag = self::endTag($name);

        return "{$startTag}{$content}{$endTag}";
    }

    /**
     * Creates string with start tag.
     *
     * @param string                $name
     * @param array<string, string> $attributes
     *
     * @return string
     */
    public static function startTag(string $name, array $attributes = []): string
    {
        $tagName = self::prepareTagName($name);
        $tagAttributes = self::prepareAttributes($attributes);

        return "<{$tagName}{$tagAttributes}>";
    }

    /**
     * Creates string with end tag.
     *
     * @param string $name
     *
     * @return string
     */
    public static function endTag(string $name): string
    {
        $tagName = self::prepareTagName($name);

        return "</{$tagName}>";
    }

    /**
     * Creates string for self closed html tag.
     *
     * @param string                $name
     * @param array<string, string> $attributes
     *
     * @return string
     */
    public static function selfClosedTag(string $name, array $attributes = []): string
    {
        $tagName = self::prepareTagName($name);
        $tagAttributes = self::prepareAttributes($attributes);

        return "<{$tagName}{$tagAttributes} />";
    }

    /**
     * Checks and prepares tag name.
     *
     * @param string $name
     *
     * @return string
     */
    private static function prepareTagName(string $name): string
    {
        if (!self::isAttributeKeyValid($name)) {
            $message = sprintf("Tag name '%s' is invalid.", $name);
            throw new InvalidArgumentException($message);
        }

        return $name;
    }

    /**
     * Creates string from an associative array of attributes.
     *
     * @param array<string, string> $attributes
     *
     * @return string
     */
    private static function prepareAttributes(array $attributes): string
    {
        $return = '';
        foreach ($attributes as $key => $value) {
            if (!self::isAttributeKeyValid($key)) {
                $message = sprintf("Attribute name '%s' is invalid.", $key);
                throw new InvalidArgumentException($message);
            }
            $quotedValue = self::quoteAttributeValue($value);
            $return .= " {$key}=\"{$quotedValue}\"";
        }

        return $return;
    }

    /**
     * Checks that string is a valid attribute key.
     *
     * @param string $key
     *
     * @return bool
     */
    private static function isAttributeKeyValid(string $key): bool
    {
        return preg_match('#^[a-zA-Z_]{1}[a-zA-Z0-9_\-]+$#', $key) === 1;
    }

    /**
     * Quotes attribute value to use in html elements.
     *
     * @param string $value
     *
     * @return string
     */
    public static function quoteAttributeValue(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES);
    }
}
