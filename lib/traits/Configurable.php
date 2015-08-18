<?php

namespace serviform\traits;

/**
 * Trait for items wihich can be configurated
 */
trait Configurable
{
	/**
	 * @param array $options
	 */
	public function config(array $options)
	{
		$properties = array();
		$reflect = new \ReflectionObject($this);
		foreach ($reflect->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
			$properties[$prop->getName()] = true;
		}
		foreach ($options as $name => $value) {
			$setter = 'set' . ucfirst($name);
			if (isset($properties[$name])) {
				$this->$name = $value;
			} elseif (method_exists($this, $setter)) {
				$this->$setter($value);
			}
		}
	}
}