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
	 * @var array
	 */
	protected $_listItemsOptions = array();


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
	 * @param array $list
	 */
	public function setListItemsOptions(array $list)
	{
		$this->_listItemsOptions = $list;
	}

	/**
	 * @return array
	 */
	public function getListItemsOptions()
	{
		return $this->_listItemsOptions;
	}


	/**
	 * @return mixed
	 */
	public function getValue()
	{
		$value = parent::getValue();
		if ($this->isMultiple()) {
			return is_array($value) ? $value : array($value);
		} else {
			return is_array($value) ? reset($value) : $value;
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
