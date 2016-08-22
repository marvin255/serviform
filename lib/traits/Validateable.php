<?php

namespace serviform\traits;

/**
 * Trait for items which can be validated
 */
trait Validateable
{
	use \serviform\traits\Childable;


	/**
	 * @var array
	 */
	protected $_validators = null;
	/**
	 * @var array
	 */
	protected $_rules = array();


	/**
	 * @return bool
	 */
	public function validate(array $toValidate = null)
	{
		$return = true;
		$validators = $this->getValidators();
		foreach ($validators as $validator) {
			$res = $validator->validate($elements);
			if ($res === false && $return === true) $return = false;
		}
		$elements = $this->getElements();
		foreach ($elements as $el) {
			if (
				(!empty($toValidate) && !in_array($el->getName(), $toValidate))
				|| !($el instanceof \serviform\IValidateable)
			){
				continue;
			}
			$res = $el->validate();
			if ($res === false && $return === true) $return = false;
		}
		return $return;
	}

	/**
	 * @return array
	 */
	public function getValidators()
	{
		if ($this->_validators === null) {
			$this->_validators = array();
			foreach ($this->getRules() as $rule) {
				$rule['type'] = trim($rule[1]);
				$rule['elements'] = is_array($rule[0]) ? $rule[0] : null;
				unset($rule[0], $rule[1]);
				$this->_validators[] = $this->createValidator($rule);
			}
		}
		return $this->_validators;
	}

	/**
	 * @param array $rules
	 */
	public function setRules(array $rules)
	{
		$this->_rules = $rules;
	}

	/**
	 * @return array
	 */
	public function getRules()
	{
		return $this->_rules;
	}

	/**
	 * @param array $options
	 */
	protected function createValidator(array $options)
	{
		$options['parent'] = $this;
		return \serviform\helpers\FactoryValidators::init($options);
	}
}
