<?php

class FileTest extends \tests\cases\Field
{
    public function getInputProvider()
    {
        return [
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
                    ],
                    'name' => 'test',
                ],
                '<input class="&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;" type="file" value="" name="test">',
            ],
        ];
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

    public function testConfigValue()
    {
        $field = $this->getField();
        $field->config(['value' => 'test']);
        $this->assertEquals([], $field->getValue());
    }

    protected function getField()
    {
        return new \serviform\fields\File();
    }
}
