<?php

class HtmlTextTest extends \tests\cases\Field
{
    public function getInputProvider()
    {
        return [
            'simple field' => [
                [
                    'name' => 'test',
                    'value' => 'test',
                ],
                '<div>test</div>'
            ],
            'field with attributes' => [
                [
                    'attributes' => [
                        'class' => 'test',
                        'data-param' => 1,
                    ],
                    'name' => 'test',
                    'value' => 'test',
                ],
                '<div class="test" data-param="1">test</div>'
            ],
        ];
    }


    protected function getField()
    {
        return new \serviform\fields\HtmlText;
    }
}
