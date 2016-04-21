<?php

namespace serviform\traits;

/**
 * Trait for items with list of values
 */
trait Listable
{
	/**
	 * @var bool
	 */
	public $multiple = false;
	/**
	 * @var array
	 */
	protected $_list = array();


	/**
	 * @param array $list
	 */
	public function setList(array $list)
	{
		$this->_list = $list;
	}

	/**
	 * @return array
	 */
	public function getList()
	{
		return $this->_list;
	}

	/**
	 * @param mixed $value
	 */
	public function setValue($value)
	{
		$items = array_keys($this->getList());
		if (!$this->isMultiple()) {
			parent::setValue($value);
		} elseif (is_array($value)) {
			$toSet = array();
			foreach ($value as $val) $toSet[] = $val;
			parent::setValue($toSet);
		}
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		$value = parent::getValue();
		if ($this->isMultiple()) {
			return is_array($value) ? $value : array();
		} else {
			return $value;
		}
	}

	/**
	 * @return string
	 */
	public function getNameChainString()
	{
		$return = parent::getNameChainString();
		if ($this->isMultiple()) {
			$return .= '[]';
		}
		return $return;
	}

	/**
	 * @return bool
	 */
	protected function isMultiple()
	{
		return $this->multiple ? true : false;
	}
}
