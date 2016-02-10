<?php

namespace serviform\fields;

use \serviform\helpers\Html;

/**
 * File class
 */
class File extends Input
{
	/**
	 * @return string
	 */
	public function getInput()
	{
		$this->setAttribute('type', 'file');
		return parent::getInput();
	}

	/**
	 * @return array
	 */
	public function getValue()
	{
		$values = $_FILES;
		$arName = $this->getFullName();
		foreach ($arName as $name) {
			if (isset($values[$name])) {
				$values = $values[$name];
			} else {
				$values = null;
			}
		}
		return $values;
	}
}