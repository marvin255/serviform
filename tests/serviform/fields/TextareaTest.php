<?php

class TextareaTest extends \tests\cases\Field
{
    public function getInputProvider()
    {
        return [
            'simple field' => [
                [
                    'name' => 'test',
                ],
                '<textarea name="test"></textarea>',
            ],
            'field with attributes' => [
                [
                    'attributes' => [
                        'class' => 'test',
                        'data-param' => 1,
                    ],
                    'name' => 'test',
                ],
                '<textarea class="test" data-param="1" name="test"></textarea>',
            ],
            'xss in value' => [
                [
                    'name' => 'test',
                    'value' => '" onclick="alert(\'xss\')" data-param="',
                ],
                '<textarea name="test">&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;</textarea>',
            ],
            'xss in attribute' => [
                [
                    'attributes' => [
                        'data-param' => '" onclick="alert(\'xss\')" data-param="',
                    ],
                    'name' => 'test',
                ],
                '<textarea data-param="&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;" name="test"></textarea>',
            ],
            'template' => [
                [
                    'template' => __DIR__.'/../../files/template.php',
                ],
                "test_template\n"
            ],
        ];
    }

    protected function getField()
    {
        return new \serviform\fields\Textarea();
    }
}
