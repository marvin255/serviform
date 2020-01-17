<?php

namespace marvin255\serviform\tests\serviform\validators;

use marvin255\serviform\helpers\FactoryValidators;
use marvin255\serviform\tests\cases\Validator;

class CompareTest extends Validator
{
    public function testSetOperator()
    {
        $validator = $this->getValidator();
        $this->assertSame($validator, $validator->setOperator('  === '));
        $this->assertSame('===', $validator->getOperator());
        $this->assertSame($validator, $validator->setOperator('>'));
        $this->assertSame('>', $validator->getOperator());
    }

    public function testSetCompareAttribute()
    {
        $validator = $this->getValidator();
        $this->assertSame($validator, $validator->setCompareAttribute('test'));
        $this->assertSame('test', $validator->getCompareAttribute());
        $this->assertSame($validator, $validator->setCompareAttribute('  test '));
        $this->assertSame('test', $validator->getCompareAttribute());
    }

    public function testSetCompareValue()
    {
        $validator = $this->getValidator();
        $this->assertSame($validator, $validator->setCompareValue('test'));
        $this->assertSame('test', $validator->getCompareValue());
        $this->assertSame($validator, $validator->setCompareValue('  test '));
        $this->assertSame('  test ', $validator->getCompareValue());
    }

    /**
     * Return array values to test validate.
     */
    protected function getValidatorProvider()
    {
        return [
            '==' => [
                3,
                true,
                [
                    'operator' => '==',
                    'compareAttribute' => null,
                    'compareValue' => '3',
                ],
            ],
            'not ==' => [
                3,
                false,
                [
                    'operator' => '==',
                    'compareAttribute' => null,
                    'compareValue' => 4,
                ],
            ],
            '!=' => [
                3,
                true,
                [
                    'operator' => '!=',
                    'compareAttribute' => null,
                    'compareValue' => '4',
                ],
            ],
            'not !=' => [
                3,
                true,
                [
                    'operator' => '!=',
                    'compareAttribute' => null,
                    'compareValue' => 4,
                ],
            ],
            '===' => [
                3,
                true,
                [
                    'operator' => '===',
                    'compareAttribute' => null,
                    'compareValue' => 3,
                ],
            ],
            'not ===' => [
                '4',
                false,
                [
                    'operator' => '===',
                    'compareAttribute' => null,
                    'compareValue' => 4,
                ],
            ],
            '!==' => [
                3,
                true,
                [
                    'operator' => '!==',
                    'compareAttribute' => null,
                    'compareValue' => '3',
                ],
            ],
            'not !==' => [
                4,
                false,
                [
                    'operator' => '!==',
                    'compareAttribute' => null,
                    'compareValue' => 4,
                ],
            ],
            '>' => [
                3,
                true,
                [
                    'operator' => '>',
                    'compareAttribute' => null,
                    'compareValue' => 2,
                ],
            ],
            'not >' => [
                4,
                false,
                [
                    'operator' => '>',
                    'compareAttribute' => null,
                    'compareValue' => 5,
                ],
            ],
            '>=' => [
                3,
                true,
                [
                    'operator' => '>=',
                    'compareAttribute' => null,
                    'compareValue' => 3,
                ],
            ],
            'not >=' => [
                4,
                false,
                [
                    'operator' => '>=',
                    'compareAttribute' => null,
                    'compareValue' => 5,
                ],
            ],
            '<' => [
                3,
                true,
                [
                    'operator' => '<',
                    'compareAttribute' => null,
                    'compareValue' => 5,
                ],
            ],
            'not <' => [
                4,
                false,
                [
                    'operator' => '<',
                    'compareAttribute' => null,
                    'compareValue' => 3,
                ],
            ],
            '<=' => [
                3,
                true,
                [
                    'operator' => '<=',
                    'compareAttribute' => null,
                    'compareValue' => 3,
                ],
            ],
            'not <=' => [
                4,
                false,
                [
                    'operator' => '<=',
                    'compareAttribute' => null,
                    'compareValue' => 3,
                ],
            ],
        ];
    }

    public function testValidateWithAttribute()
    {
        $parent = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\FieldHasValidators')
            ->setMethods(['getElement'])
            ->getMock();
        $field = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\Field')->getMock();
        $field->method('getValue')->will($this->returnValue(1));
        $field->method('getParent')->will($this->returnValue($parent));
        $field2 = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\Field')->getMock();
        $field2->method('getValue')->will($this->returnValue(2));
        $field2->method('getParent')->will($this->returnValue($parent));
        $parent->method('getElement')->will($this->returnCallback(function ($name) use ($field, $field2) {
            return $name === 'test2' ? $field2 : $field;
        }));
        $validator = $this->getValidator()
            ->setSkipOnError(false)
            ->setSkipOnEmpty(false)
            ->setWhen(null)
            ->setOperator('>=')
            ->setCompareValue(null)
            ->setCompareAttribute('test2')
            ->setParent($parent)
            ->setElements(['test']);
        $this->assertSame(false, $validator->validate());
    }

    public function testValidateWithWrongAttribute()
    {
        $parent = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\FieldHasValidators')
            ->setMethods(['getElement'])
            ->getMock();
        $field = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\Field')->getMock();
        $field->method('getValue')->will($this->returnValue(1));
        $field->method('getParent')->will($this->returnValue($parent));
        $parent->method('getElement')->will($this->returnCallback(function ($name) use ($field) {
            return $name === 'test' ? $field : null;
        }));
        $this->setExpectedException('\InvalidArgumentException');
        $validator = $this->getValidator()
            ->setSkipOnError(false)
            ->setSkipOnEmpty(false)
            ->setWhen(null)
            ->setOperator('>=')
            ->setCompareValue(null)
            ->setCompareAttribute('test2')
            ->setParent($parent)
            ->setElements(['test'])
            ->validate();
    }

    /**
     * Return object for validator representation.
     */
    protected function getValidator(array $options = [])
    {
        $type = '\\marvin255\\serviform\\validators\\Compare';

        return FactoryValidators::initElement($type, $options);
    }
}
