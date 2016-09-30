<?php

namespace serviform\fields;

use serviform\helpers\Html;

/**
 * Single checkbox class.
 */
class Checkbox extends \serviform\FieldBase
{
    /**
     * @var string
     */
    public $trueValue = 1;
    /**
     * @var string
     */
    public $falseValue = 0;

    /**
     * @return string
     */
    public function getInput()
    {
        $res = $this->renderTemplate();
        if ($res !== null) {
            return $res;
        }

        $value = $this->getValue();
        $options = $this->getAttributes();
        $options['name'] = $this->getNameChainString();
        $options['value'] = $this->trueValue;
        $options['type'] = 'checkbox';
        if ($value == $this->trueValue) {
            $options['checked'] = 'checked';
        }
        $option = Html::tag('input', $options, false);
        $disableOptions = array('type' => 'hidden', 'name' => $options['name'], 'value' => $this->falseValue);
        $disableOption = Html::tag('input', $disableOptions, false);

        return $disableOption.$option;
    }
}
