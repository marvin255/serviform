<?php

namespace marvin255\serviform\fields;

use marvin255\serviform\helpers\Html;
use marvin255\serviform\abstracts\Field;

/**
 * Textarea class.
 */
class Textarea extends Field
{
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
