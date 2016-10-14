<?php

class MultipleTest extends \tests\cases\Field
{
    public function getInputProvider()
    {
        return [
            'simple field' => [
                [
                    'name' => 'test',
                    'min' => 2,
                ],
                '<div><div><input class="multiplier" value="" name="test[0]" type="text"></div><div><input class="multiplier" value="" name="test[1]" type="text"></div></div>',
            ],
            'xss in item attributes' => [
                [
                    'name' => 'test',
                    'min' => 1,
                    'itemAttributes' => [
                        'class' => '" onclick="" param="',
                    ],
                ],
                '<div><div class="&quot; onclick=&quot;&quot; param=&quot;"><input class="multiplier" value="" name="test[0]" type="text"></div></div>',
            ],
            'template' => [
                [
                    'template' => __DIR__.'/../../files/template.php',
                ],
                "test_template\n"
            ],
        ];
    }

    /**
     * @expectedException \serviform\Exception
     */
    public function testNoMultiplierException()
    {
        $field = $this->getField();
        $field->setMultiplier([]);
        $field->getInput();
    }

    public function testGetElement()
    {
        $field = $this->getField();
        $this->assertInstanceOf('\serviform\IElement', $field->getElement(0));
        $this->assertEquals($field, $field->getElement(0)->getParent());
        $this->assertInstanceOf('\serviform\IElement', $field->getElement(1));
        $this->assertEquals($field, $field->getElement(1)->getParent());
        $this->assertInstanceOf('\serviform\IElement', $field->getElement(2));
        $this->assertEquals($field, $field->getElement(2)->getParent());
        $this->assertEquals(null, $field->getElement(3));
    }

    public function testGetElements()
    {
        $field = $this->getField();
        $field->min = 3;
        $elements = $field->getElements();
        $this->assertEquals(3, count($elements));
        foreach ($elements as $element) {
            $this->assertInstanceOf('\serviform\IElement', $element);
            $this->assertEquals($field, $element->getParent());
        }
    }

    public function testAddError()
    {
        $field = $this->getField();
        $field->addError('test');
        $this->assertEquals([], $field->getErrors());
    }

    public function testGetErrors()
    {
        $field = $this->getField();
        $field->getElement(0)->addError('test 1');
        $field->getElement(1)->addError('test 2');
        $field->getElement(2)->addError('test 3');
        $this->assertEquals(
            [
                ['test 1'],
                ['test 2'],
                ['test 3'],
            ],
            $field->getErrors()
        );
    }

    public function testClearErrors()
    {
        $field = $this->getField();
        $field->getElement(0)->addError('test 1');
        $field->getElement(1)->addError('test 2');
        $field->getElement(2)->addError('test 3');
        $field->clearErrors();
        $this->assertEquals([], $field->getErrors());
    }

    public function testSetValue()
    {
        $field = $this->getField();
        $field->setValue([0 => 'test', 1 => 'test1']);
        $this->assertEquals([0 => 'test', 1 => 'test1'], $field->getValue());
    }

    public function testSetMultiplier()
    {
        $field = $this->getField();
        $field->setMultiplier(['type' => 'input']);
        $this->assertEquals(['type' => 'input'], $field->getMultiplier());
    }

    public function testSetItemAttributes()
    {
        $field = $this->getField();
        $field->setItemAttributes(['class' => 'test']);
        $this->assertEquals(['class' => 'test'], $field->getItemAttributes());
    }

    public function testConfigValue()
    {
        $field = $this->getField();
        $field->config(['value' => [0 => 'test', 1 => 'test1']]);
        $this->assertEquals([0 => 'test', 1 => 'test1'], $field->getValue());
    }

    public function testConfigMin()
    {
        $field = $this->getField();
        $field->config(['min' => 1]);
        $this->assertEquals(1, $field->min);
    }

    public function testConfigMax()
    {
        $field = $this->getField();
        $field->config(['max' => 1]);
        $this->assertEquals(1, $field->max);
    }

    public function testConfigMultiplier()
    {
        $field = $this->getField();
        $field->config(['multiplier' => ['type' => 'input']]);
        $this->assertEquals(['type' => 'input'], $field->getMultiplier());
    }

    public function testConfigItemAttributes()
    {
        $field = $this->getField();
        $field->config(['itemAttributes' => ['class' => 'test']]);
        $this->assertEquals(['class' => 'test'], $field->getItemAttributes());
    }

    protected function getField()
    {
        $field = new \serviform\fields\Multiple();
        $field->config([
            'max' => 3,
            'multiplier' => [
                'type' => 'input',
                'name' => 'multiplier',
                'attributes' => [
                    'class' => 'multiplier',
                ],
            ],
        ]);

        return $field;
    }
}
