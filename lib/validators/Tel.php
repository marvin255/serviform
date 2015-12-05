<?php

namespace serviform\validators;

/**
 * Tel validator class
 */
class Tel extends \serviform\ValidatorBase
{
	/**
	 * @var string
	 */
	public $errorMessage = 'Wrong tel format';


	/**
	 * @param mixed $value
	 * @param \serviform\IValidator $element
	 * @return bool
	 */
	protected function vaidateValue($value, $element)
	{
		if ($value !== null && $value !== '')
			return (bool) preg_match('/^\+7\(9\d{2}\)\d{3}\-\d{2}\-\d{2}$/', $value);
		}
		return true;
	}
}