<?php

class FormTest extends \tests\cases\Field
{
    /**
     * @dataProvider getInputProvider
     */
    public function testGetInput($options, $expected)
    {
        $field = $this->getField();
        $field->config($options);
        $this->assertEquals($expected, $field->getBeginTag());
        $this->assertEquals('</form>', $field->getEndTag());
    }

    public function getInputProvider()
    {
        return [
            'simple field' => [
                [
                    'attributes' => [
                        'action' => '/test/',
                    ],
                ],
                '<form action="/test/">',
            ],
            'xss in attribute' => [
                [
                    'attributes' => [
                        'action' => '" onclick="" param="',
                    ],
                ],
                '<form action="&quot; onclick=&quot;&quot; param=&quot;">',
            ],
        ];
    }

    public function testGetInputFromTemplate()
    {
        $field = $this->getField();
        $field->setTemplate(__DIR__.'/../../files/template.php');
        $this->assertEquals("test_template\n", $field->getInput());
    }

    public function testGetInputFordefaultTemplate()
    {
        $field = $this->getField();
        $this->assertStringStartsWith('<form class=" form-horizontal">', $field->getInput());
    }

    public function testLoadData()
    {
        $field = $this->getField();
        $field->setName('form');
        $field->loadData(['form' => [
            'child' => 'test',
            'child3' => 'test3',
        ]]);
        $this->assertEquals(['child' => 'test', 'child2' => null], $field->getValue());
        $field->setElements([
            'child' => [
                'type' => 'form',
                'elements' => [
                    'child_child_1' => [
                        'type' => 'input',
                    ],
                    'child_child_2' => [
                        'type' => 'input',
                    ],
                ],
            ],
            'child2' => [
                'type' => 'form',
                'elements' => [
                    'child2_child_1' => [
                        'type' => 'input',
                    ],
                    'child2_child_2' => [
                        'type' => 'input',
                    ],
                ],
            ],
        ]);
        $field->getElement('child2')->loadData(['form' => [
            'child' => [
                'child_child_1' => 'test',
                'child_child_2' => 'test_2',
            ],
        ]]);
        $this->assertEquals([
            'child2_child_1' => null,
            'child2_child_2' => null,
        ], $field->getElement('child2')->getValue());
    }

    public function testGetElement()
    {
        $field = $this->getField();
        $this->assertInstanceOf('\serviform\IElement', $field->getElement('child'));
        $this->assertEquals($field, $field->getElement('child')->getParent());
        $this->assertInstanceOf('\serviform\IElement', $field->getElement('child2'));
        $this->assertEquals($field, $field->getElement('child2')->getParent());
        $this->assertEquals(null, $field->getElement('child3'));
    }

    public function testGetElements()
    {
        $field = $this->getField();
        $elements = $field->getElements();
        $this->assertEquals(2, count($elements));
        foreach ($elements as $element) {
            $this->assertInstanceOf('\serviform\IElement', $element);
            $this->assertEquals($field, $element->getParent());
        }
    }

    public function testUnsetElements()
    {
        $field = $this->getField();
        $field->unsetElement('child');
        $elements = $field->getElements();
        $this->assertEquals(1, count($elements));
        $this->assertArrayHasKey('child2', $elements);
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
        $field->getElement('child')->addError('test 1');
        $field->getElement('child2')->addError('test 2');
        $this->assertEquals(
            [
                'child' => ['test 1'],
                'child2' => ['test 2'],
            ],
            $field->getErrors()
        );
    }

    public function testClearErrors()
    {
        $field = $this->getField();
        $field->getElement('child')->addError('test 1');
        $field->getElement('child2')->addError('test 2');
        $field->clearErrors();
        $this->assertEquals([], $field->getErrors());
    }

    public function testSetValue()
    {
        $field = $this->getField();
        $field->setValue(['child' => 'test', 'child3' => 'test1']);
        $this->assertEquals(['child' => 'test', 'child2' => null], $field->getValue());
    }

    /**
     * @expectedException \serviform\Exception
     */
    public function testSetNotArrayValue()
    {
        $field = $this->getField();
        $field->setValue(1111);
    }

    public function testConfigValue()
    {
        $field = $this->getField();
        $field->config(['value' => ['child' => 'test', 1 => 'test1']]);
        $this->assertEquals(['child' => 'test', 'child2' => null], $field->getValue());
    }

    protected function getField()
    {
        $field = new \serviform\fields\Form();
        $field->config([
            'elements' => [
                'child' => [
                    'type' => 'input',
                    'attributes' => [
                        'class' => 'child',
                    ],
                ],
                'child2' => [
                    'type' => 'input',
                    'attributes' => [
                        'class' => 'child',
                    ],
                ],
            ],
        ]);

        return $field;
    }
}
