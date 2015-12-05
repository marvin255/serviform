<?php

namespace serviform\validators;

/**
 * Filter validator class
 */
class Filter extends \serviform\ValidatorBase
{
	/**
	 * @var string
	 */
	public $filter = null;


	/**
	 * @param mixed $value
	 * @param \serviform\IValidator $element
	 * @return bool
	 */
	protected function vaidateValue($value, $element)
	{
		if ($this->filter)
			call_user_func_array($this->filter, [$value, $element]);
		}
		return true;
	}
}