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
		if (!is_array($value)) $value = array($value);

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
		} elseif (empty($this->fieldParams['MULTIPLE']) || $this->fieldParams['MULTIPLE'] !== 'Y') {
			$return = preg_replace('/name="(.+)\[\]"/', 'name="$1"', $return);
			$return = preg_replace('/name="(.+)\[n[^\[\]]+\]"/', 'name="$1"', $return);
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
}