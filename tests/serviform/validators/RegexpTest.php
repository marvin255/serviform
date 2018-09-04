<?php

namespace marvin255\serviform\tests\serviform\validators;

use marvin255\serviform\tests\cases\Validator;
use marvin255\serviform\helpers\FactoryValidators;

class RegexpTest extends Validator
{
    public function testSetRegexp()
    {
        $validator = $this->getValidator();
        $this->assertSame($validator, $validator->setRegexp('test'));
        $this->assertSame('test', $validator->getRegexp());
        $this->assertSame($validator, $validator->setRegexp(' #test# '));
        $this->assertSame('test', $validator->getRegexp());
    }

    public function testSetModifiers()
    {
        $validator = $this->getValidator();
        $this->assertSame($validator, $validator->setModifiers('test'));
        $this->assertSame('test', $validator->getModifiers());
        $this->assertSame($validator, $validator->setModifiers(' #test# '));
        $this->assertSame('test', $validator->getModifiers());
    }

    /**
     * Return array values to test validate.
     */
    protected function getValidatorProvider()
    {
        return [
            'true regexp' => [
                '+3A ',
                true,
                [
                    'regexp' => '\+\d\S\s',
                    'modifiers' => 'i',
                ],
            ],
            'false regexp' => [
                '+3A',
                false,
                [
                    'regexp' => '\+\d\S\s',
                    'modifiers' => 'i',
                ],
            ],
            'modifiers' => [
                'AAA',
                false,
                [
                    'regexp' => '[a-z]+',
                    'modifiers' => '',
                ],
            ],
            'empty regexp' => [
                'AAA',
                false,
                [
                    'regexp' => '',
                    'modifiers' => 'i',
                ],
            ],
            'builtin email' => [
                'test@test.ru',
                true,
                [
                    'regexp' => 'email',
                    'modifiers' => 'i',
                ],
            ],
            'builtin url' => [
                'http://test.ru',
                true,
                [
                    'regexp' => 'url',
                    'modifiers' => 'i',
                ],
            ],
            'builtin ipv4' => [
                '127.0.0.1',
                true,
                [
                    'regexp' => 'ipv4',
                    'modifiers' => 'i',
                ],
            ],
            'builtin ipv6' => [
                '2001:0db8:11a3:09d7:1f34:8a2e:07a0:765d',
                true,
                [
                    'regexp' => 'ipv6',
                    'modifiers' => 'i',
                ],
            ],
        ];
    }

    /**
     * Return object for validator representation.
     */
    protected function getValidator(array $options = [])
    {
        $type = '\\marvin255\\serviform\\validators\\Regexp';

        return FactoryValidators::initElement($type, $options);
    }
}
