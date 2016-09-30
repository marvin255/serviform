<?php

namespace serviform\fields;

use serviform\helpers\Html;

/**
 * Button class.
 */
class Button extends \serviform\FieldBase
{
    /**
     * @var bool
     */
    public $allowHtmlContent = false;

    /**
     * @return string
     */
    public function getInput()
    {
        $res = $this->renderTemplate();
        if ($res !== null) {
            return $res;
        }

        $options = $this->getAttributes();
        $options['name'] = $this->getNameChainString();
        $value = $this->getValue();
        if ($value !== null) {
            $options['value'] = $value;
        }

        return Html::tag(
            'button',
            $options,
            $this->allowHtmlContent ? $this->getLabel() : Html::clearText($this->getLabel())
        );
    }
}
