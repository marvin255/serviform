<?php

namespace serviform;

/**
 * Validateable element interface
 */
interface IValidateable extends IChildable
{
	/**
	 * @return bool
	 */
	public function validate(array $toValidate = null);


	/**
	 * @param array $rules
	 */
	public function setRules(array $rules);

	/**
	 * @return array
	 */
	public function getRules();
}
