<?php

namespace marvin255\serviform\tests\serviform\fields;

use marvin255\serviform\tests\cases\Validator;
use marvin255\serviform\helpers\FactoryValidators;

class RequiredTest extends Validator
{
    /**
     * Return array values to test validate
     */
    protected function getValidatorProvider()
    {
        return [
            'empty string' => ['', false],
            'empty array' => [[], false],
            'null' => [null, false],
            'string' => ['test', true],
            'zero' => [0, true],
            'zero string' => ['0', true],
        ];
    }

    /**
     * Return object for validator representation.
     */
    protected function getValidator()
    {
        $type = '\\marvin255\\serviform\\validators\\Required';

        return FactoryValidators::initElement($type);
    }
}
