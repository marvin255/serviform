<?php

namespace serviform\validators;

/**
 * Require validator class
 */
class Required extends \serviform\ValidatorBase
{
	/**
	 * @var string
	 */
	public $errorMessage = 'Field is required';


	/**
	 * @param mixed $value
	 * @param \serviform\IValidator $element
	 * @return bool
	 */
	protected function vaidateValue($value, $element)
	{
		return $value !== null && trim($value) !== '';
	}
}