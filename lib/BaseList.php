<?php

namespace serviform;

/**
 * BaseList element class
 */
abstract class BaseList extends BaseRenderable
{
	/**
	 * @var array
	 */
	protected $_list = array();



	/**
	 * @return bool
	 */
	abstract protected function isMultiple();



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
			if (in_array($value, $items)) parent::setValue($value);
		} elseif (is_array($value)) {
			$toSet = array();
			foreach ($value as $val)
				if (in_array($val, $items)) $toSet[] = $val;
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
}