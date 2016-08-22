<?php

namespace serviform;

/**
 * Validator that's sets data to fields
 */
interface IValidatorWithData
{
	/**
	 * Sets data to fields
	 */
	public function setValidatorData();
}
