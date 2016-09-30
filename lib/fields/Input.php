<?php

namespace serviform\fields;

use serviform\helpers\Html;

/**
 * Input class.
 */
class Input extends \serviform\FieldBase
{
    /**
     * @return string
     */
    public function getInput()
    {
        $res = $this->renderTemplate();
        if ($res !== null) {
            return $res;
        }

        $attrubutes = $this->getAttributes();
        $attrubutes['value'] = $this->getValue();
        $attrubutes['name'] = $this->getNameChainString();
        if (empty($attrubutes['type'])) {
            $attrubutes['type'] = 'text';
        }

        return Html::tag('input', $attrubutes, false);
    }
}
