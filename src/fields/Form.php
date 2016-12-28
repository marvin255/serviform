<?php

namespace marvin255\serviform\fields;

use marvin255\serviform\traits\HasValidators as TField;
use marvin255\serviform\interfaces\Field;
use marvin255\serviform\interfaces\HasChildren;
use marvin255\serviform\interfaces\HasValidators;

/**
 * Form class.
 */
class Form implements Field, HasChildren, HasValidators
{
    use TField;

    /**
     * @param array $values
     */
    public function loadData(array $values = null)
    {
        $valueSet = false;
        $values = is_array($values) ? $values : $_REQUEST;
        $arName = $this->getFullName();
        foreach ($arName as $name) {
            if (isset($values[$name])) {
                $values = $values[$name];
            } else {
                $values = array();
            }
        }
        if (is_array($values)) {
            $this->setValue($values);
            $elements = $this->getElements();
            foreach ($values as $key => $v) {
                if (isset($elements[$key])) {
                    $valueSet = true;
                    break;
                }
            }
        }

        return $valueSet;
    }
}
