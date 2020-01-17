<?php

namespace marvin255\serviform\tests\cases;

use marvin255\serviform\tests\BaseTestCase;

abstract class Field extends BaseTestCase
{
    /**
     * @param array $options
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    abstract protected function getField(array $options);

    protected $templateFile = null;

    public function setUp()
    {
        $this->templateFile = tempnam(sys_get_temp_dir(), mt_rand());
        file_put_contents($this->templateFile, "<?php echo \"test template\";\r\n");
    }

    public function tearDown()
    {
        unlink($this->templateFile);
    }

    public function getInputProvider()
    {
        $rand = mt_rand();

        return [
            'callback render' => [
                [
                    'template' => function ($field) use ($rand) {
                        return $rand;
                    },
                ],
                $rand,
            ],
            'template render' => [
                [
                    'template' => $this->templateFile,
                ],
                'test template',
            ],
        ];
    }

    public function testGetInput()
    {
        $data = $this->getInputProvider();
        foreach ($data as $message => $value) {
            $field = $this->getField($value[0]);
            $this->assertEquals($value[1], $field->getInput(), $message);
        }
    }

    public function testToString()
    {
        $data = $this->getInputProvider();
        foreach ($data as $message => $value) {
            $field = $this->getField($value[0]);
            $this->assertEquals($value[1], (string) $field, $message);
        }
    }

    public function testSetTemplate()
    {
        $field = $this->getField();
        $this->assertSame($field, $field->setTemplate(__DIR__));
        $this->assertSame(__DIR__, $field->getTemplate());
    }

    public function testSetParent()
    {
        $field = $this->getField();
        $field2 = $this->getField();
        $this->assertSame($field, $field->setParent($field2));
        $this->assertSame($field2, $field->getParent());
    }

    public function testSetValue()
    {
        $field = $this->getField();
        $this->assertSame($field, $field->setValue('test'));
        $this->assertSame('test', $field->getValue());
    }

    public function testSetAttribute()
    {
        $field = $this->getField();
        $this->assertSame($field, $field->setAttribute('data-1', 1));
        $this->assertSame($field, $field->setAttribute('data-1', 2));
        $this->assertSame($field, $field->setAttribute('data-2', '3'));
        $this->assertSame(2, $field->getAttribute('data-1'));
        $this->assertSame('3', $field->getAttribute('data-2'));
    }

    public function testAddToAttribute()
    {
        $field = $this->getField();
        $field->setAttribute('data-1', 1);
        $this->assertSame($field, $field->addToAttribute('data-1', ' 2'));
        $this->assertSame('1 2', $field->getAttribute('data-1'));
    }

    public function testSetAttributes()
    {
        $field = $this->getField();
        $data = [
            'data-1' => '1',
            'data-2' => 2,
        ];
        $this->assertSame($field, $field->setAttributes($data));
        $this->assertSame($data, $field->getAttributes());
    }

    public function testSetAttributesClearPreviousData()
    {
        $field = $this->getField();
        $data = ['data-3' => 1, 'data-4' => 2, 'data-5' => '3'];
        $field->setAttributes([
            'data-1' => '1',
            'data-2' => 2,
        ]);
        $field->setAttributes($data);
        $this->assertSame($data, $field->getAttributes());
    }

    public function testGetAttributesWithSetAttribute()
    {
        $field = $this->getField();
        $field->setAttribute('data-1', 1);
        $field->setAttribute('data-2', 2);
        $field->setAttribute('data-3', '3');
        $this->assertSame(
            ['data-1' => 1, 'data-2' => 2, 'data-3' => '3'],
            $field->getAttributes()
        );
    }

    public function testSetName()
    {
        $field = $this->getField();
        $this->assertSame($field, $field->setName('test_name'));
        $this->assertSame('test_name', $field->getName());
    }

    public function testGetFullName()
    {
        $field = $this->getField();
        $field->setName('child');
        $this->assertSame(['child'], $field->getFullName());

        $parent1 = $this->getField();
        $parent1->setName('parent1');
        $field->setParent($parent1);
        $this->assertSame(['parent1', 'child'], $field->getFullName());

        $parent2 = $this->getField();
        $parent2->setName('parent2');
        $parent1->setParent($parent2);
        $this->assertSame(['parent2', 'parent1', 'child'], $field->getFullName());
    }

    public function testGetNameChainString()
    {
        $field = $this->getField();
        $field->setName('child');
        $this->assertSame('child', $field->getNameChainString());

        $parent1 = $this->getField();
        $parent1->setName('parent1');
        $field->setParent($parent1);
        $this->assertSame('parent1[child]', $field->getNameChainString());

        $parent2 = $this->getField();
        $parent2->setName('parent2');
        $parent1->setParent($parent2);
        $this->assertSame('parent2[parent1][child]', $field->getNameChainString());
    }

    public function testAddError()
    {
        $field = $this->getField();
        $this->assertSame($field, $field->addError('test error'));
        $this->assertSame(['test error'], $field->getErrors());
    }

    public function testClearErrors()
    {
        $field = $this->getField();
        $field->addError('test error');
        $field->addError('test error 1');
        $this->assertSame($field, $field->clearErrors());
        $this->assertSame([], $field->getErrors());
    }

    public function testSetLabel()
    {
        $field = $this->getField();
        $this->assertSame($field, $field->setLabel('test label'));
        $this->assertSame('test label', $field->getLabel());
    }

    public function testJsonSerialize()
    {
        $field = $this->getField();
        $field->setAttribute('data-test', 'test');
        $field->setName('name');
        $field->addError('test');
        $field->setValue('value');
        $field->setLabel('label');
        $class = get_class($field);
        $class = explode('\\', $class);
        $class = end($class);
        $toTest = json_encode([
            'type' => strtolower($class),
            'attributes' => $field->getAttributes(),
            'name' => $field->getName(),
            'fullName' => $field->getFullName(),
            'errors' => $field->getErrors(),
            'value' => $field->getValue(),
            'label' => $field->getLabel(),
        ]);
        $this->assertSame($toTest, json_encode($field));
    }
}
