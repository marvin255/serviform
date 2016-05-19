<?php

class ButtonTest extends \tests\cases\Field
{
    public function getInputProvider()
    {
        return [
            'simple field' => [
                [
                    'name' => 'test',
                    'label' => 'label',
                ],
                '<button name="test">label</button>'
            ],
            'field with attributes' => [
                [
                    'attributes' => [
                        'type' => 'submit',
                        'class' => 'test',
                        'data-param' => 1,
                    ],
                    'name' => 'test',
                ],
                '<button type="submit" class="test" data-param="1" name="test"></button>'
            ],
            'xss in label' => [
                [
                    'name' => 'test',
                    'label' => '" onclick="alert(\'xss\')" data-param="',
                    'allowHtmlContent' => false,
                ],
                '<button name="test">&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;</button>'
            ],
            'xss in attribute' => [
                [
                    'attributes' => [
                        'type' => '" onclick="alert(\'xss\')" data-param="',
                    ],
                    'name' => 'test',
                ],
                '<button type="&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;" name="test"></button>'
            ],
            'allow html in label' => [
                [
                    'name' => 'test',
                    'label' => '" onclick="alert(\'xss\')" data-param="',
                    'allowHtmlContent' => true,
                ],
                '<button name="test">" onclick="alert(\'xss\')" data-param="</button>'
            ]
        ];
    }

    public function testConfigAllowHtmlContent()
    {
        $field = $this->getField();
        $field->config(['allowHtmlContent' => true]);
        $this->assertEquals(true, $field->allowHtmlContent);
        $field->config(['allowHtmlContent' => false]);
        $this->assertEquals(false, $field->allowHtmlContent);
    }

    protected function getField()
    {
        return new \serviform\fields\Button;
    }
}
