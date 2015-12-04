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
	 * @return bool
	 */
	protected function vaidateValue($value)
	{
		return $value !== null && $value !== '';
	}
}