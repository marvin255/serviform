<?php

namespace marvin255\serviform\fields;

use marvin255\serviform\helpers\Html;
use marvin255\serviform\abstracts\Field;

/**
 * Input class.
 */
class Input extends Field
{
    /**
     * @return string
     */
    protected function renderInternal()
    {
        $attrubutes = $this->getAttributes();
        $attrubutes['value'] = $this->getValue();
        $attrubutes['name'] = $this->getNameChainString();
        if (empty($attrubutes['type'])) {
            $attrubutes['type'] = 'text';
        }

        return Html::createTag('input', $attrubutes, false);
    }
}
