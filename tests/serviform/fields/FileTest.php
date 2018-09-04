<?php

namespace marvin255\serviform\tests\serviform\fields;

use marvin255\serviform\tests\cases\Field;
use marvin255\serviform\helpers\FactoryFields;

class FileTest extends Field
{
    public function getInputProvider()
    {
        $parent = parent::getInputProvider();

        return array_merge($parent, [
            'simple field' => [
                [
                    'name' => 'test',
                ],
                '<input type="file" value="" name="test">',
            ],
            'field with attributes' => [
                [
                    'attributes' => [
                        'type' => 'email',
                        'class' => 'test',
                        'data-param' => 1,
                    ],
                    'name' => 'test',
                ],
                '<input type="file" class="test" data-param="1" value="" name="test">',
            ],
            'xss in attribute' => [
                [
                    'attributes' => [
                        'class' => '" onclick="alert(\'xss\')" data-param="',
                        'data" onclick="alert(\'xss\')" data-param="' => 'xss',
                    ],
                    'name' => 'test',
                ],
                '<input class="&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;" data--onclick--alert--xss----data-param="xss" type="file" value="" name="test">',
            ],
       ]);
    }

    /**
     * @param array $options
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    protected function getField(array $options = [])
    {
        $type = '\\marvin255\\serviform\\fields\\File';

        return FactoryFields::initElement($type, $options);
    }

    public function testSetValue()
    {
        $field = $this->getField();
        $field->setValue('test');
        $this->assertEquals([], $field->getValue());
    }

    public function testGetValue()
    {
        $field = $this->getField();
        $field->setName('test');
        $_FILES['test'] = [
            'name' => 'MyFile.txt',
            'type' => 'text/plain',
            'tmp_name' => '/tmp/php/php1h4j1o',
            'error' => 0,
            'size' => 123,
        ];
        $this->assertEquals($_FILES['test'], $field->getValue());
    }

    public function testGetValueWithSubNames()
    {
        $parent = $this->getMockBuilder('\\marvin255\\serviform\\abstracts\\FieldHasValidators')
            ->setMethods(['getName'])
            ->getMock();
        $parent->method('getName')
            ->will($this->returnValue('parent'));

        $field = $this->getField();
        $field->setName('test');
        $field->setParent($parent);

        $_FILES['parent'] = [
            'name' => [
                'test' => 'MyFile.txt',
            ],
            'type' => [
                'test' => 'text/plain',
            ],
            'tmp_name' => [
                'test' => '/tmp/php/php1h4j1o',
            ],
            'error' => [
                'test' => 0,
            ],
            'size' => [
                'test' => 123,
            ],
        ];
        $this->assertEquals([
            'name' => 'MyFile.txt',
            'type' => 'text/plain',
            'tmp_name' => '/tmp/php/php1h4j1o',
            'error' => 0,
            'size' => 123,
        ], $field->getValue());
    }
}
