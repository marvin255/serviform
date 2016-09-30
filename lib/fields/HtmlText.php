<?php

namespace serviform\fields;

use serviform\helpers\Html;

/**
 * Html text class.
 */
class HtmlText extends \serviform\FieldBase
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

        return Html::tag('div', $attrubutes, $this->getValue());
    }
}
