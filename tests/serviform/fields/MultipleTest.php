<?php

namespace marvin255\serviform\tests\serviform\fields;

use marvin255\serviform\tests\cases\FieldWithValidators;
use marvin255\serviform\helpers\FactoryFields;

class MultipleTest extends FieldWithValidators
{
    /**
     * @param array $options
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    protected function getField(array $options = array())
    {
        $type = '\\marvin255\\serviform\\fields\\Multiple';

        return FactoryFields::initElement($type, $options);
    }
}
