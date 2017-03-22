<?php

namespace marvin255\serviform\tests\serviform\validators;

use marvin255\serviform\tests\cases\ValidatorElementValue;
use marvin255\serviform\helpers\FactoryValidators;

class FilterTest extends ValidatorElementValue
{
    public function testSetFilter()
    {
        $validator = $this->getValidator();
        $this->assertSame($validator, $validator->setFilter('trim'));
        $this->assertSame('trim', $validator->getFilter());
        $f = function ($value, $element) {
        };
        $this->assertSame($validator, $validator->setFilter($f));
        $this->assertSame($f, $validator->getFilter());
    }

    /**
     * Return array values to test validate
     */
    protected function getValidatorProvider()
    {
        return [
            'string filter' => ['   test    ', 'test', ['filter' => 'trim']],
            'string filter intval' => ['12.5', 12, ['filter' => 'intval']],
            'callback' => [
                'test input',
                'test callback',
                [
                    'filter' => function ($value, $element) {
                        if ($value === 'test input') {
                            $element->setValue('test callback');
                        }
                        return true;
                    }
                ]
            ],
        ];
    }

    /**
     * Return object for validator representation.
     */
    protected function getValidator(array $options = array())
    {
        $type = '\\marvin255\\serviform\\validators\\Filter';

        return FactoryValidators::initElement($type, $options);
    }
}
