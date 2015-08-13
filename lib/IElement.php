<?php

namespace serviform;

/**
 * Element interface
 */
interface IElement
{
	/**
	 * @return string
	 */
	public function getInput();



	/**
	 * @param \serviform\IElement $parent
	 */
	public function setParent(\serviform\IElement $parent);

	/**
	 * @return \serviform\IElement
	 */
	public function getParent();



	/**
	 * @param mixed $value
	 */
	public function setValue($value);

	/**
	 * @return mixed
	 */
	public function getValue();
	


	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function setAttribute($name, $value);

	/**
	 * @param array $attributes
	 */
	public function setAttributes(array $attributes);

	/**
	 * @param string $name
	 * @return mixed
	 */
	public function getAttribute($name);

	/**
	 * @return array
	 */
	public function getAttributes();



	/**
	 * @param string $name
	 */
	public function setName($name);

	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @return array
	 */
	public function getFullName();

	/**
	 * @return string
	 */
	public function getNameChainString();



	/**
	 * @param string $error
	 */
	public function addError($error);

	/**
	 * @return array
	 */
	public function getErrors();

	/**
	 * @return null
	 */
	public function clearErrors();



	/**
	 * @param string $label
	 */
	public function setLabel($label);

	/**
	 * @return string
	 */
	public function getLabel();



	/**
	 * @param array $options
	 */
	public function config(array $options);
}