<?php

declare(strict_types=1);

namespace Marvin255\Serviform\Tests\ParameterBag;

use InvalidArgumentException;
use Marvin255\Serviform\ParameterBag\ParameterBagArray;
use Marvin255\Serviform\Tests\BaseTestCase;

class ParameterBagArrayTest extends BaseTestCase
{
    public function testGet(): void
    {
        $name = 'test_param';
        $value = 'test_param_value';

        $bag = new ParameterBagArray(
            [
                $name => $value,
            ]
        );
        $gettedValue = $bag->get($name);

        $this->assertSame($value, $gettedValue);
    }

    public function testGetNotFoundException(): void
    {
        $bag = new ParameterBagArray();

        $this->expectException(InvalidArgumentException::class);
        $bag->get('test');
    }

    public function testSet(): void
    {
        $name = 'test_param';
        $value = 'test_param_value';

        $bag = new ParameterBagArray();
        $bag->set($name, $value);
        $gettedValue = $bag->get($name);

        $this->assertSame($value, $gettedValue);
    }

    public function testHas(): void
    {
        $name = 'test_param';
        $value = 'test_param_value';

        $bag = new ParameterBagArray(
            [
                $name => $value,
            ]
        );
        $hasResult = $bag->has($name);

        $this->assertTrue($hasResult);
    }

    public function testHasNot(): void
    {
        $name = 'test_param';
        $value = 'test_param_value';

        $bag = new ParameterBagArray(
            [
                $name => $value,
            ]
        );
        $hasResult = $bag->has($name . '_not_exist');

        $this->assertFalse($hasResult);
    }

    public function testToArray(): void
    {
        $name = 'test_param';
        $value = 'test_param_value';
        $name1 = 'test_param_1';
        $value1 = 'test_param_value_1';

        $bag = new ParameterBagArray(
            [
                $name => $value,
            ]
        );
        $bag->set($name1, $value1);
        $array = $bag->toArray();

        $this->assertSame(
            [
                $name => $value,
                $name1 => $value1,
            ],
            $array
        );
    }
}
