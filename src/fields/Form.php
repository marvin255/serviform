<?php

namespace marvin255\serviform\fields;

use marvin255\serviform\interfaces\Field as IField;
use marvin255\serviform\traits\HasChildren as TField;
use marvin255\serviform\interfaces\HasChildren;

/**
 * Form class.
 */
class Form implements IField, HasChildren
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
