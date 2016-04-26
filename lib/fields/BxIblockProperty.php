<?php

namespace serviform\fields;

use \serviform\helpers\Html;

/**
 * Single checkbox class
 */
class BxIblockProperty extends \serviform\FieldBase
{
	/**
	 * @param array
	 */
	public $fieldParams = null;


	/**
	 * @return string
	 */
	public function getInput()
	{
		$res = $this->renderTemplate();
		if ($res !== null) return $res;

		global $APPLICATION;

		require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/iblock/prolog.php');
		\Bitrix\Main\Loader::includeModule('iblock');
		IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/modules/iblock/admin/iblock_element_edit_compat.php');
		$APPLICATION->AddHeadScript('/bitrix/js/iblock/iblock_edit.js');

		$value = $this->getValue();
		if ($this->fieldParams['USER_TYPE'] && !is_array($value)) {
			$value = array(0 => array('VALUE' => $value, 'DESCRIPTION' => null));
			$this->fieldParams['~VALUE'] = $this->fieldParams['VALUE'] = $value;
		} elseif (!is_array($value)) {
			$value = array($value);
		}

		ob_start();
		ob_implicit_flush(false);
		\_ShowPropertyField(
			$this->getNameChainString(),
			$this->fieldParams,
			$value
		);
		$return = ob_get_clean();

		if ($this->fieldParams['USER_TYPE'] === 'map_yandex') {
			$return = preg_replace(
				'/name="point_map_yandex_' . $this->fieldParams['CODE'] . '_' . $this->fieldParams['ID'] . '__([A-Za-z0-1]+)_([A-Za-z0-1]+)"/',
				'name="' . $this->getNameChainString() . '[$1][$2]"',
				$return
			);
		} elseif ($this->fieldParams['USER_TYPE'] === 'HTML') {
			$return = preg_replace(
				'/name="PROP_' . $this->fieldParams['ID'] . '__(n?\d+)__VALUE__TEXT_"/',
				'name="' . $this->getNameChainString() . '[$1][TEXT]"',
				$return
			);
			$return = preg_replace(
				'/name="PROP_' . $this->fieldParams['ID'] . '__(n?\d+)__VALUE__TYPE_"/',
				'name="' . $this->getNameChainString() . '[$1][TYPE]"',
				$return
			);
		} elseif (empty($this->fieldParams['MULTIPLE']) || $this->fieldParams['MULTIPLE'] !== 'Y') {
			$return = preg_replace('/name="(.+)\[\]"/', 'name="$1"', $return);
			$return = preg_replace('/name="(.+)\[n[^\[\]]+\]"/', 'name="$1"', $return);
			$return = preg_replace('/name="PROP.+\[VALUE\]"/', 'name="' . $this->getNameChainString() . '[0]"', $return);
			$return = preg_replace('/(BX\.calendar\(.*)PROP.+\[VALUE\]/', '$1' . $this->getNameChainString() . '[0]', $return);
		}

		return $return;
	}

	public function setValue($value)
	{
		if ($this->fieldParams['USER_TYPE'] === 'map_yandex') {
			if (is_array($value)) {
				$first = reset($value);
				if (isset($first['lat'])) {
					$new = array();
					foreach ($value as $val) {
						$new[] = floatval($val['lat']) . ',' . floatval($val['lon']);
					}
					$value = $new;
				}
				$this->fieldParams['~VALUE'] = $value;
			} else {
				$this->fieldParams['~VALUE'] = array($value);
			}
		}
		return parent::setValue($value);
	}

	public function getValue()
	{
		if ($this->fieldParams['PROPERTY_TYPE'] == 'F' && isset($_REQUEST[$this->getName() . '_del'][0]) && $_REQUEST[$this->getName() . '_del'][0] == 'Y') {
			return ['del' => 'Y'];
		} elseif ($this->fieldParams['PROPERTY_TYPE'] == 'F' && !empty($_FILES[$this->getName()])) {
			$res = [];
			foreach ($_FILES[$this->getName()] as $property => $ar) {
				foreach ($ar as $key => $value) {
					$res["n{$key}"][$property] = $value;
				}
			}
			$res = empty($this->fieldParams['MULTIPLE']) || $this->fieldParams['MULTIPLE'] !== 'Y' ? reset($res) : $res;
			return $res;
		} elseif ($this->fieldParams['PROPERTY_TYPE'] == 'N') {
			$val = parent::getValue();
			if (is_array($val)) {
				foreach ($val as $key => $v)
					$val[$key] = (float) str_replace(',', '.', $v);
			} else {
				$val = (float) str_replace(',', '.', $val);
			}
			return $val;
		} else {
			return parent::getValue();
		}
	}
}
