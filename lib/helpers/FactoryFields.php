<?php

namespace serviform\helpers;

/**
 * Factory for fields
 */
class FactoryFields
{
	/**
	 * @param string $type
	 * @param array $options
	 * @return \serviform\IElement
	 */
	protected static function initElement($type, array $options = array())
	{
		if (strpos($type, '\\') !== false) {
			$class = $type;
		} elseif ($class = self::getFieldDescription($type)) {
			$class = $class['type'];
		}
		if (is_subclass_of($class, '\\serviform\\IElement')) {
			$item = new $class;
			$item->config($options);
			return $item;
		} else {
			throw new \serviform\Exception('Wrong class: ' . $class);
		}
	}

	/**
	 * @param array $options
	 * @return \serviform\IElement
	 */
	public static function init(array $options)
	{
		if (!empty($options['type'])) {
			$type = $options['type'];
			unset($options['type']);
			return self::initElement($type, $options);
		} else {
			throw new \serviform\Exception('Wrong field type');
		}
	}

	/**
	 * @var array
	 */
	protected static $_descriptions = [
		'button' => ['type' => '\serviform\fields\Button'],
		'checkbox' => ['type' => '\serviform\fields\Checkbox'],
		'file' => ['type' => '\serviform\fields\File'],
		'form' => ['type' => '\serviform\fields\Form'],
		'formGrouped' => ['type' => '\serviform\fields\FormGrouped'],
		'htmlText' => ['type' => '\serviform\fields\HtmlText'],
		'input' => ['type' => '\serviform\fields\Input'],
		'multiple' => ['type' => '\serviform\fields\Multiple'],
		'radioList' => ['type' => '\serviform\fields\RadioList'],
		'select' => ['type' => '\serviform\fields\Select'],
		'textarea' => ['type' => '\serviform\fields\Textarea'],
	];

	/**
	 * @param string $namespace
	 * @param array $options
	 */
	public function setFieldDescription($name, array $options)
	{
		if (empty($options['type']) || !class_exists($options['type'])) {
			throw new \serviform\Exception('Class does not exist');
		}
		self::$_descriptions[$name] = $options;
	}

	/**
	 * @param string $name
	 * @return array
	 */
	public function getFieldDescription($name)
	{
		return isset(self::$_descriptions[$name]) ? self::$_descriptions[$name] : null;
	}
}
