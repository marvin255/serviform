<?php

namespace serviform\traits;

/**
 * Trait for items which have html attributes
 */
trait Attributable
{
	/**
	 * @var array
	 */
	protected $_attributes = array();


	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function setAttribute($name, $value)
	{
		$this->_attributes[$name] = $value;
	}

	/**
	 * @param array $attributes
	 */
	public function setAttributes(array $attributes)
	{
		foreach ($attributes as $name => $value) {
			$this->setAttribute($name, $value);
		}
	}

	/**
	 * @param string $name
	 * @return mixed
	 */
	public function getAttribute($name)
	{
		$attributes = $this->getAttributes();
		return isset($attributes[$name]) ? $attributes[$name] : null;
	}

	/**
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->_attributes;
	}
}
