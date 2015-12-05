<?php

namespace serviform;

/**
 * Base validator class
 */
abstract class ValidatorBase implements IValidator
{
	use \serviform\traits\Configurable;


	/**
	 * @var string
	 */
	public $errorMessage = 'Error';
	/**
	 * @var \serviform\IValidateable
	 */
	protected $_parent = null;
	/**
	 * @var array
	 */
	protected $_elements = null;


	/**
	 * @param mixed $value
	 * @return bool
	 */
	abstract protected function vaidateValue($value);


	/**
	 * @return bool
	 */
	public function validate()
	{
		$return = true;
		$elementNames = $this->getElements();
		$parent = $this->getParent();
		foreach ($elementNames as $elementName) {
			$element = $parent->getElement($elementName);
			if ($element !== null) {
				$value = $element->getValue();
				$res = $this->vaidateValue($value, $element);
				if ($res === false) {
					$return = false;
					$element->addError($this->errorMessage);
				}
			} else {
				throw new Exception('Wrong validated field name');
			}
		}
		return $return;
	}


	/**
	 * @param \serviform\IValidateable $parent
	 */
	public function setParent(\serviform\IValidateable $parent)
	{
		$this->_parent = $parent;
	}

	/**
	 * @return \serviform\IValidateable
	 */
	public function getParent()
	{
		return $this->_parent;
	}

	/**
	 * @param array $elements
	 */
	public function setElements(array $elements)
	{
		$this->_elements = $elements;
	}

	/**
	 * @return array
	 */
	public function getElements()
	{
		return $this->_elements;
	}
}