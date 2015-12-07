<?php

namespace serviform\validators;

/**
 * Numeric validator class
 */
class Numeric extends \serviform\ValidatorBase
{
	/**
	 * @var string
	 */
	public $errorMessage = 'Wrong number format';
	/**
	 * @var float
	 */
	public $maxLength = null;
	/**
	 * @var float
	 */
	public $minLength = null;


	/**
	 * @param mixed $value
	 * @param \serviform\IValidator $element
	 * @return bool
	 */
	protected function vaidateValue($value, $element)
	{
		if ($value !== null && $value !== '') {
			if (is_numeric($value)) {
				if ($this->maxLength !== null && strlen($value) > $this->maxLength) {
					return false;
				} elseif ($this->minLength !== null && strlen($value) < $this->minLength) {
					return false;
				}
				return true;
			}
			return false;
		}
		return true;
	}
}