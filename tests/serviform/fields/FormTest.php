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

    public function testToString()
    {
        $field = $this->getField();
        $field->setTemplate(__DIR__.'/../../files/template.php');
        $this->assertEquals("test_template\n", (string) $field);
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

    public function testSetElements()
    {
        $field = $this->getField();
        $field->setElements([
            'test' => ['type' => 'input'],
        ]);
        $elements = $field->getElements();
        $this->assertEquals(1, count($elements));
    }

    /**
     * @expectedException \serviform\Exception
     */
    public function testSetWrongChildType()
    {
        $field = $this->getField();
        $field->setElement('wrong_element', 11);
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

    public function testGetValidators()
    {
        $field = $this->getField();
        $field->setRules([
            [['child'], 'required'],
            [['child2'], 'filter', 'filter' => 'trim'],
        ]);
        $validators = $field->getValidators();
        $this->assertEquals(2, count($validators));
        foreach ($validators as $validator) {
            $this->assertInstanceOf('\serviform\IValidator', $validator);
            $this->assertEquals($field, $validator->getParent());
        }
    }

    public function testValidate()
    {
        $rules = [
            [['child'], 'required', 'message' => 'test_error'],
            [['child2'], 'filter', 'filter' => 'trim'],
        ];
        $values = ['child' => '', 'child2' => '  test  '];

        $field = $this->getField();
        $field->setRules($rules);
        $field->setValue(['child' => '11', 'child2' => '  test  ']);
        $res = $field->validate();
        $this->assertEquals($res, true);
        $this->assertEquals([], $field->getErrors());
        $this->assertEquals(['child' => '11', 'child2' => 'test'], $field->getValue());

        $field = $this->getField();
        $field->setRules($rules);
        $field->setValue($values);
        $res = $field->validate();
        $this->assertEquals($res, false);
        $this->assertEquals(['child' => ['test_error']], $field->getErrors());
        $this->assertEquals(['child' => '', 'child2' => 'test'], $field->getValue());

        $field = $this->getField();
        $field->setRules($rules);
        $field->setValue($values);
        $res = $field->validate(['child']);
        $this->assertEquals($res, false);
        $this->assertEquals(['child' => ['test_error']], $field->getErrors());
        $this->assertEquals(['child' => '', 'child2' => '  test  '], $field->getValue());

        $field = $this->getField();
        $field->setRules($rules);
        $field->setValue(['child' => '11', 'child2' => '  test  ']);
        $field->setElement('form', [
            'type' => 'form',
            'elements' => [
                'inner' => ['type' => 'input'],
            ],
            'rules' => [
                [['inner'], 'required', 'message' => 'test_error'],
            ],
        ]);
        $res = $field->validate();
        $this->assertEquals($res, false);
        $this->assertEquals(['form' => ['inner' => ['test_error']]], $field->getErrors());
        $this->assertEquals(['child' => '11', 'child2' => 'test', 'form' => ['inner' => '']], $field->getValue());
    }

    public function testSetValidatorsData()
    {
        $rules = [
            [['child'], '\ValidatorWithData'],
            [['child'], 'required'],
        ];

        $field = $this->getField();
        $field->setRules($rules);
        $field->setValidatorsData();
        $input = $field->getElement('child')->getAttributes();
        $this->assertEquals(['class' => 'child', 'ValidatorWithData' => 'set'], $input);

        $field = $this->getField();
        $field->setRules($rules);
        $field->setElement('form', [
            'type' => 'form',
            'elements' => [
                'inner' => ['type' => 'input'],
            ],
            'rules' => [
                [['inner'], '\ValidatorWithData'],
            ],
        ]);
        $field->setValidatorsData();
        $input = $field->getElement('child')->getAttributes();
        $this->assertEquals(['class' => 'child', 'ValidatorWithData' => 'set'], $input);
        $input = $field->getElement('form')->getElement('inner')->getAttributes();
        $this->assertEquals(['ValidatorWithData' => 'set'], $input);
    }

    public function testConfigValue()
    {
        $field = $this->getField();
        $field->config(['value' => ['child' => 'test', 1 => 'test1']]);
        $this->assertEquals(['child' => 'test', 'child2' => null], $field->getValue());
    }

    public function testConfigRules()
    {
        $field = $this->getField();
        $field->config(['rules' => ['rule1' => [], 'rule1' => []]]);
        $this->assertEquals(['rule1' => [], 'rule1' => []], $field->getRules());
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

    public function testJsonSerialize()
    {
        $field = $this->getField();
        $field->setAttribute('data-test', 'test');
        $field->addError('test');
        $errors = $field->getErrors();
        $elements = [];
        foreach ($field->getelements() as $key => $element) {
            $elements[$key] = $element->jsonSerialize();
        }

        $toTest = json_encode([
            'name' => $field->getName(),
            'fullName' => $field->getFullName(),
            'errors' => $errors ? $errors : null,
            'label' => $field->getLabel(),
            'attributes' => $field->getAttributes(),
            'elements' => $elements,
        ]);
        $this->assertEquals($toTest, json_encode($field));
    }
}
