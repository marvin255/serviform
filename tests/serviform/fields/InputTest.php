<?php

class InputTest extends \tests\cases\Field
{
    public function getInputProvider()
    {
        return [
            'simple field' => [
                [
                    'name' => 'test',
                ],
                '<input value="" name="test" type="text">',
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
                '<input type="email" class="test" data-param="1" value="" name="test">',
            ],
            'xss in value' => [
                [
                    'name' => 'test',
                    'value' => '" onclick="alert(\'xss\')" data-param="',
                ],
                '<input value="&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;" name="test" type="text">',
            ],
            'xss in attribute' => [
                [
                    'attributes' => [
                        'type' => '" onclick="alert(\'xss\')" data-param="',
                    ],
                    'name' => 'test',
                ],
                '<input type="&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;" value="" name="test">',
            ],
        ];
    }

    protected function getField()
    {
        return new \serviform\fields\Input();
    }
}
