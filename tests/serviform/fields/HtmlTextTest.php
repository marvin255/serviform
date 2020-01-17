<?php

namespace marvin255\serviform\tests\serviform\fields;

use marvin255\serviform\helpers\FactoryFields;
use marvin255\serviform\tests\cases\Field;

class HtmlTextTest extends Field
{
    public function getInputProvider()
    {
        $parent = parent::getInputProvider();

        return array_merge($parent, [
            'simple field' => [
                [
                    'name' => 'test',
                    'value' => 'test',
                ],
                '<div>test</div>',
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
                '<div class="test" data-param="1">test</div>',
            ],
            'xss in attributes' => [
                [
                    'attributes' => [
                        'class' => '" onclick="alert(\'xss\')" data-param="',
                        'data" onclick="alert(\'xss\')" data-param="' => 'xss',
                    ],
                    'name' => 'test',
                    'value' => '',
                ],
                '<div class="&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;" data--onclick--alert--xss----data-param="xss"></div>',
            ],
            'no html content' => [
                [
                    'name' => 'test',
                    'value' => '<span>test</span>',
                    'allowHtmlContent' => false,
                ],
                '<div>&lt;span&gt;test&lt;/span&gt;</div>',
            ],
            'html content' => [
                [
                    'name' => 'test',
                    'value' => '<span>test</span>',
                    'allowHtmlContent' => true,
                ],
                '<div><span>test</span></div>',
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
        $type = '\\marvin255\\serviform\\fields\\HtmlText';

        return FactoryFields::initElement($type, $options);
    }
}
