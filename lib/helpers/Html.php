<?php

namespace serviform\helpers;

/**
 * Html helper
 */
class Html
{
	/**
	 * @param string $tag
	 * @param array $htmlOptions
	 * @param string $content
	 * @return string
	 */
	public static function tag($tag, $htmlOptions = null, $content = null)
	{
		$return = '';
		$tag = self::clearAttribute(trim($tag));
		if ($tag) {
			$attributes = self::createAttributes($htmlOptions);
			$return = '<' . $tag . (!empty($attributes) ? ' ' . $attributes : '');
			if ($content === false) {
				$return .= '>';
			} elseif ($content === true) {
				$return .= '>';
			} else {
				$return .= '>' . $content . self::closeTag($tag);
			}
		}
		return $return;
	}

	/**
	 * @param string $tag
	 * @return string
	 */
	public static function closeTag($tag)
	{
		return '</' . self::clearAttribute(trim($tag)) . '>';
	}

	/**
	 * @param string $string
	 * @return string
	 */
	public static function toId($string)
	{
		$return = strtolower(trim(self::clearAttribute(str_replace(array('[', ']'), '_', trim($string))), '_'));
		return $return;
	}

	/**
	 * @param array $attributes
	 * @return string
	 */
	public static function createAttributes($attributes)
	{
		$return = '';
		if (is_array($attributes)) {
			foreach ($attributes as $key => $value) {
				$key = self::clearAttributeKey($key);
				$value = self::clearAttribute($value);
				if ($key === '') continue;
				if ($return !== '') $return .= ' ';
				$return .= "{$key}=\"{$value}\"";
			}
		}
		return $return;
	}

	/**
	 * @param string $string
	 * @return string
	 */
	public static function clearAttribute($string)
	{
		return htmlspecialchars($string);
	}

	/**
	 * @param string $string
	 * @return string
	 */
	public static function clearAttributeKey($string)
	{
		$return = str_replace(
			array('=', '"', "'", '<', '>'),
			'_',
			$string
		);
		$return = htmlspecialchars(trim($return));
		return $return;
	}

	/**
	 * @param string $string
	 * @return string
	 */
	public static function clearText($string)
	{
		return htmlspecialchars($string);
	}
}