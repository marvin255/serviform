<?php

namespace serviform\validators;

/**
 * Date validator class
 */
class Date extends \serviform\ValidatorBase
{
	/**
	 * @var string
	 */
	public $errorMessage = 'Wrong date format';
	/**
	 * @var string
	 */
	public $convertToformat = null;


	/**
	 * @param mixed $value
	 * @param \serviform\IValidator $element
	 * @return bool
	 */
	protected function vaidateValue($value, $element)
	{
		if ($value !== null && $value !== '')
			if (($time = strtotime($value)) !== false) {
				if ($this->convertToformat !== null) {
					$element->setValue(date($this->convertToformat, $time));
				}
				return true;
			}
			return false;
		}
		return true;
	}
}