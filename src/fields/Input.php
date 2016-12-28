<?php

namespace marvin255\serviform\fields;

use marvin255\serviform\helpers\Html;
use marvin255\serviform\interfaces\Field as IField;
use marvin255\serviform\traits\Field as TField;

/**
 * Input class.
 */
class Input implements IField
{
    use TField;

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
