<?php

namespace marvin255\serviform\tests\serviform\validators;

use marvin255\serviform\tests\cases\Validator;
use marvin255\serviform\helpers\FactoryValidators;

class RangeTest extends Validator
{
    public function testSetNot()
    {
        $validator = $this->getValidator();
        $this->assertSame($validator, $validator->setNot(''));
        $this->assertSame(false, $validator->getNot());
        $this->assertSame($validator, $validator->setNot(true));
        $this->assertSame(true, $validator->getNot());
    }

    public function testSetStrict()
    {
        $validator = $this->getValidator();
        $this->assertSame($validator, $validator->setStrict(''));
        $this->assertSame(false, $validator->getStrict());
        $this->assertSame($validator, $validator->setStrict(true));
        $this->assertSame(true, $validator->getStrict());
    }

    public function testSetRange()
    {
        $validator = $this->getValidator();
        $this->assertSame($validator, $validator->setRange([1, 2, 3]));
        $this->assertSame([1, 2, 3], $validator->getRange());
    }

    public function testSetRangeWithWrongParam()
    {
        $validator = $this->getValidator();
        $this->setExpectedException('\InvalidArgumentException');
        $this->assertSame($validator, $validator->setRange(1));
    }

    /**
     * Return array values to test validate
     */
    protected function getValidatorProvider()
    {
        return [
            'not in range' => [
                3,
                false,
                [
                    'range' => [1, 2],
                    'strict' => false,
                    'not' => false,
                ]
            ],
            'in range' => [
                3,
                true,
                [
                    'range' => [1, 2, 3],
                    'strict' => false,
                    'not' => false,
                ]
            ],
            'not in range with not' => [
                3,
                true,
                [
                    'range' => [1, 2],
                    'strict' => false,
                    'not' => true,
                ]
            ],
            'in range with not' => [
                3,
                false,
                [
                    'range' => [1, 2, 3],
                    'strict' => false,
                    'not' => true,
                ]
            ],
            'not in range with strict' => [
                '3',
                false,
                [
                    'range' => [1, 2, 3],
                    'strict' => true,
                    'not' => false,
                ]
            ],
            'in range with strict' => [
                3,
                true,
                [
                    'range' => [1, 2, 3],
                    'strict' => true,
                    'not' => false,
                ]
            ],
        ];
    }

    /**
     * Return object for validator representation.
     */
    protected function getValidator(array $options = array())
    {
        $type = '\\marvin255\\serviform\\validators\\Range';

        return FactoryValidators::initElement($type, $options);
    }
}
