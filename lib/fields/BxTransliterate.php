<?php

namespace serviform\fields;

use \serviform\helpers\Html;

/**
 * Bitrix field with transliteration class
 */
class BxTransliterate extends \serviform\fields\Input
{
	/**
	 * @var string
	 */
	public $id = 'f-code';
	/**
	 * @var string
	 */
	public $transliterateFromId = 'f-name';
	/**
	 * @var array
	 */
	public $arTranslit = array(
		'TRANS_LEN' => 100,
		'TRANS_CASE' => 'L',
		'TRANS_SPACE' => '-',
		'TRANS_OTHER' => '-',
		'TRANS_EAT' => 'Y',
	);



	/**
	 * @return string
	 */
	public function getInput()
	{
		$this->setAttribute('id', $this->id);
		$this->setAttribute('onfocus', 'if (this.value === \'\') transliterate();');
		\CJSCore::Init(array('translit'));
		$eat = $this->arTranslit['TRANS_EAT'] == 'Y'? 'true': 'false';
		$input = <<<EOD
<script type="text/javascript">
		function transliterate()
		{
			var from = document.getElementById('{$this->transliterateFromId}');
			var to = document.getElementById('{$this->id}');
			if (from && to) {
				BX.translit(from.value, {
					'max_len' : '{$this->arTranslit['TRANS_LEN']}',
					'change_case' : '{$this->arTranslit['TRANS_CASE']}',
					'replace_space' : '{$this->arTranslit['TRANS_SPACE']}',
					'replace_other' : '{$this->arTranslit['TRANS_OTHER']}',
					'delete_repeat_replace' : {$eat},
					'use_google' : false,
					'callback' : function(result){to.value = result;}
				});
			}
		}
		</script>
EOD;
		$input .= parent::getInput();
		$input .= '<img id="name_link" class="linked" src="/bitrix/themes/.default/icons/iblock/link.gif" onclick="transliterate();" />';
		return $input;
	}
}