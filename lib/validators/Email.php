<?php

namespace serviform\validators;

/**
 * Email validator class
 */
class Email extends \serviform\ValidatorBase
{
	/**
	 * @var string
	 */
	public $errorMessage = 'Wrong email format';


	/**
	 * @param mixed $value
	 * @param \serviform\IValidator $element
	 * @return bool
	 */
	protected function vaidateValue($value, $element)
	{
		if ($value !== null && $value !== '') {
			return (bool) preg_match('/^[^@]+@[0-9\-a-zA-Z_]+\.[a-zA-Z]{2,6}$/', $value);
		}
		return true;
	}
}