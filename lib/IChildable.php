<?php

namespace serviform;

/**
 * Element which has children
 */
interface IChildable
{
	/**
	 * @param array $elements
	 */
	public function setElements(array $elements);

	/**
	 * @param string $name
	 * @param array|\serviform\interfaces\FormComponent $element
	 */
	public function setElement($name, $element);

	/**
	 * @return array
	 */
	public function getElements();

	/**
	 * @param string $name
	 * @return \serviform\interfaces\FormComponent|null
	 */
	public function getElement($name);

	/**
	 * @param string $name
	 */
	public function unsetElement($name);
}