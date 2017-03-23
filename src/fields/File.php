<?php

namespace marvin255\serviform\fields;

use marvin255\serviform\helpers\Html;
use marvin255\serviform\abstracts\Field;

/**
 * File class.
 */
class File extends Field
{
    /**
     * @return string
     */
    protected function renderInternal()
    {
        $attrubutes = $this->getAttributes();
        $attrubutes['type'] = 'file';
        $attrubutes['value'] = $this->getValue();
        $attrubutes['name'] = $this->getNameChainString();

        return Html::createTag('input', $attrubutes, false);
    }

    /**
     * @return array
     */
    public function getValue()
    {
        $values = $_FILES;
        $arName = $this->getFullName();
        foreach ($arName as $name) {
            if (isset($values[$name])) {
                $values = $values[$name];
            } elseif (isset($values['tmp_name'][$name])) {
                $newValues = [];
                foreach ($values as $fileParam => $fileValue) {
                    $newValues[$fileParam] = $fileValue[$name];
                }
                $values = $newValues;
            } else {
                $values = null;
            }
        }

        return $values;
    }
}
