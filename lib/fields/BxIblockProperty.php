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

		ob_start();
		ob_implicit_flush(false);
		\_ShowPropertyField(
			$this->getNameChainString(),
			$this->fieldParams,
			$this->getValue()
		);
		$return = ob_get_clean();

		if (empty($this->fieldParams['MULTIPLE']) || $this->fieldParams['MULTIPLE'] !== 'Y') {
			$return = preg_replace('/name="(.+)\[\]"/', 'name="$1"', $return);
			$return = preg_replace('/name="(.+)\[n[^\[\]]+\]"/', 'name="$1"', $return);
		}

		return $return;
	}
}