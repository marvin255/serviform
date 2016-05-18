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
		$tag = self::toId(trim($tag));
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
		return '</' . self::toId($tag) . '>';
	}

	/**
	 * @param string $string
	 * @return string
	 */
	public static function toId($string)
	{
		$return = strtolower(self::clearAttributeKey(str_replace(array('[', ']'), '_', $string)));
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
		return self::clearText($string);
	}

	/**
	 * @param string $string
	 * @return string
	 */
	public static function clearAttributeKey($string)
	{
		$return = str_replace(
			array(
				'=',
				'"',
				"'",
				'<',
				'>',
				'&',
				' ',
				'(',
				')',
				"\t",
				"\n",
				"\r",
				"\0",
				"\x0B"
			),
			'_',
			$string
		);
		return trim($return);
	}

	/**
	 * @param string $string
	 * @return string
	 */
	public static function clearText($string)
	{
		return htmlspecialchars($string, ENT_QUOTES);
	}
}
