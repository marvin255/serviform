<?php

namespace serviform;

/**
 * Validator interface
 */
interface IValidator
{
	/**
	 * @return bool
	 */
	public function validate();


	/**
	 * @param \serviform\IElement $parent
	 */
	public function setParent(\serviform\IElement $parent);

	/**
	 * @return \serviform\IElement
	 */
	public function getParent();


	/**
	 * @param array $elements
	 */
	public function setElements(array $elements);

	/**
	 * @return array
	 */
	public function getElements();


	/**
	 * @param array $options
	 */
	public function config(array $options);
}