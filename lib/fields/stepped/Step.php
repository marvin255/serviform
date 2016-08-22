<?php

namespace serviform\fields\stepped;

use \serviform\IChildable;
use \UnexpectedValueException;

/**
 * Stepped form step interface
 */
class Step implements IStep
{
	use \serviform\traits\Attributable;
	use \serviform\traits\Configurable;


	/**
	 * Returns list of elements for this step
	 * @return array
	 */
	public function getElements()
	{
		$return = [];
		$parentElements = $this->getParent()->getElements();
		$elements = $this->getElementsNames();
		foreach ($elements as $element) {
			$return[$element] = $parentElements[$element];
		}
		return $return;
	}


	/**
	 * Validates all elements for this step
	 */
	public function validateElements()
	{
		$parent = $this->getParent();
		$elements = $this->getElements();
		if (!($parent instanceof \serviform\IValidateable)) {
			throw new UnexpectedValueException('Parent is not allow validation');
		}
		$parent->validate($elements);
	}


	/**
	 * @var \serviform\IChildable
	 */
	protected $_parent = null;

	/**
	 * Sets parent for this step
	 * @param \serviform\IChildable $parent
	 */
	public function setParent(IChildable $parent)
	{
		$this->_parent = $parent;
	}

	/**
	 * Gets parent for this step
	 * @return \serviform\IChildable
	 */
	public function getParent()
	{
		if (empty($this->_parent)) {
			throw new UnexpectedValueException('Parent does not set');
		}
		return $this->_parent;
	}


	/**
	 * @var array
	 */
	protected $_elementsNames = null;

	/**
	 * Sets list of elements for this step
	 * @param array $element
	 */
	public function setElementsNames(array $elements)
	{
		$parentElements = $this->getParent()->getElements();
		foreach ($elements as $element) {
			$element = trim($element);
			if (!isset($parentElements[$element])) {
				throw new UnexpectedValueException("Parent has not element named: {$element}");
			}
			$this->_elementsNames[] = $element;
		}
	}

	/**
	 * Gets list of elements for this step
	 * @return array
	 */
	public function getElementsNames()
	{
		if (empty($this->_elementsNames)) {
			throw new UnexpectedValueException("Empty list of step's elements");
		}
		return $this->_elementsNames;
	}


	/**
	 * @var string
	 */
	protected $_title = '';

	/**
	 * Sets step title
	 * @param string $val
	 */
	public function setTitle($val)
	{
		$this->_title = trim($val);
	}

	/**
	 * Gets step title
	 * @return string
	 */
	public function getTitle()
	{
		return $this->_title;
	}


	/**
	 * @var string
	 */
	protected $_description = '';

	/**
	 * Sets step description
	 * @param string $val
	 */
	public function setDescription($val)
	{
		$this->_description = trim($val);
	}

	/**
	 * Gets step description
	 * @return string
	 */
	public function getDescription()
	{
		return $this->_description;
	}
}
