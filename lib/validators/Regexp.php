<?php

namespace serviform\validators;

/**
 * Regexp validator class
 */
class Regexp extends \serviform\ValidatorBase
{
	/**
	 * @var string
	 */
	public $regexp = null;
	/**
	 * @var string
	 */
	public $errorMessage = 'Wrong value format';


	/**
	 * @param mixed $value
	 * @param \serviform\IValidator $element
	 * @return bool
	 */
	protected function vaidateValue($value, $element)
	{
		if ($this->regexp)
			return (bool) preg_match('/^' . $this->regexp . '$/', $value);
		}
		return true;
	}
}