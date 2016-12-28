<?php

namespace marvin255\serviform\tests\serviform\fields;

use marvin255\serviform\tests\cases\Field;
use marvin255\serviform\helpers\FactoryFields;

class InputTest extends Field
{
    public function getInputProvider()
    {
        $parent = parent::getInputProvider();

        return array_merge($parent, [
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
                        'data" onclick="alert(\'xss\')" data-param="' => 'xss',
                    ],
                    'name' => 'test',
                ],
                '<input type="&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;" data--onclick--alert--xss----data-param="xss" value="" name="test">',
           ],
       ]);
    }

    /**
     * @param array $options
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    protected function getField(array $options = array())
    {
        $type = '\\marvin255\\serviform\\fields\\Input';

        return FactoryFields::initElement($type, $options);
    }
}
