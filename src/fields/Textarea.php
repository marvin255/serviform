<?php

namespace marvin255\serviform\fields;

use marvin255\serviform\helpers\Html;
use marvin255\serviform\interfaces\Field as IField;
use marvin255\serviform\traits\Field as TField;

/**
 * Textarea class.
 */
class Textarea implements IField
{
    use TField;

    /**
     * @return string
     */
    protected function renderInternal()
    {
        $attrubutes = $this->getAttributes();
        $attrubutes['name'] = $this->getNameChainString();

        return Html::createTag(
            'textarea',
            $attrubutes,
            Html::clearAttributeValue($this->getValue())
        );
    }
}
