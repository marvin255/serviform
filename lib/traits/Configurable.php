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
		foreach ($options as $name => $value) {
			$setter = 'set' . ucfirst($name);
			if (property_exists($this, $name)) {
				$this->$name = $value;
			} elseif (method_exists($this, $setter)) {
				$this->$setter($value);
			}
		}
	}
}