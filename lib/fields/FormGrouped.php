<?php

namespace serviform\fields;

use \serviform\helpers\Html;
use \InvalidArgumentException;

/**
 * Form with groups of fields
 */
class FormGrouped extends \serviform\fields\Form
{
	/**
	 * @var array
	 */
	protected $_groups = [];

	/**
	 * @return array
	 */
	public function getGroups()
	{
		return $this->_groups;
	}

	/**
	 * @param array $groups
	 */
	public function setGroups(array $groups)
	{
		$this->_groups = [];
		foreach ($groups as $key => $value) {
			$this->setGroup($key, $value);
		}
	}

	/**
	 * @param string $groupKey
	 * @param array $group
	 */
	protected function setGroup($groupKey, array $group)
	{
		if (empty($group['elements']) || !is_array($group['elements'])) {
			throw new InvalidArgumentException('Parameters elements should be an array');
		}
		$elements = [];
		foreach ($group['elements'] as $key => $value) {
			if (is_array($value)) {
				if (!$this->getElement($key)) {
					throw new InvalidArgumentException("Element {$key} does not exist in this form");
				}
				$elements[$key] = $value;
			} else {
				if (!$this->getElement($value)) {
					throw new InvalidArgumentException("Element {$value} does not exist in this form");
				}
				$elements[$value] = [];
			}
		}
		$this->_groups[$groupKey] = $group;
		$this->_groups[$groupKey]['elements'] = $elements;
	}
}
