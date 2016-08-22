<?php

namespace serviform\fields\stepped;

use \serviform\IChildable;
use \UnexpectedValueException;

/**
 * Stepped form step
 */
interface IStep
{
	/**
	 * Returns list of elements for this step
	 * @return array
	 */
	public function getElements();


	/**
	 * Validates all elements for this step
	 */
	public function validateElements();

	/**
	 * Sets parent for this step
	 * @param \serviform\IChildable $parent
	 */
	public function setParent(IChildable $parent);

	/**
	 * Gets parent for this step
	 * @return \serviform\IChildable
	 */
	public function getParent();

	/**
	 * Sets list of elements for this step
	 * @param array $element
	 */
	public function setElementsNames(array $elements);

	/**
	 * Gets list of elements for this step
	 * @return array
	 */
	public function getElementsNames();

	/**
	 * Sets step title
	 * @param string $val
	 */
	public function setTitle($val);

	/**
	 * Gets step title
	 * @return string
	 */
	public function getTitle();

	/**
	 * Sets step description
	 * @param string $val
	 */
	public function setDescription($val);

	/**
	 * Gets step description
	 * @return string
	 */
	public function getDescription();
}
