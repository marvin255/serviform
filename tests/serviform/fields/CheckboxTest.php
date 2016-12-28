<?php

namespace marvin255\serviform\tests\serviform\fields;

use marvin255\serviform\tests\cases\Field;
use marvin255\serviform\helpers\FactoryFields;

class CheckboxTest extends Field
{
    public function getInputProvider()
    {
        $parent = parent::getInputProvider();

        return array_merge($parent, [
            'simple field' => [
                [
                    'name' => 'test',
                    'label' => 'label',
                    'trueValue' => 'test_true',
                    'falseValue' => 'test_false',
                ],
                '<input type="hidden" name="test" value="test_false"><input name="test" value="test_true" type="checkbox">',
            ],
            'field with attributes' => [
                [
                    'attributes' => [
                        'class' => 'test',
                        'data-param' => 1,
                    ],
                    'name' => 'test',
                    'trueValue' => 1,
                    'falseValue' => 0,
                ],
                '<input type="hidden" name="test" value="0"><input class="test" data-param="1" name="test" value="1" type="checkbox">',
            ],
            'xss in value' => [
                [
                    'name' => 'test',
                    'value' => '" onclick="alert(\'xss\')" data-param="',
                    'trueValue' => 1,
                    'falseValue' => 0,
                ],
                '<input type="hidden" name="test" value="0"><input name="test" value="1" type="checkbox">',
            ],
            'xss in attribute' => [
                [
                    'attributes' => [
                        'class' => '" onclick="alert(\'xss\')" data-param="',
                        'data" onclick="alert(\'xss\')" data-param="' => 'xss',
                    ],
                    'name' => 'test',
                    'trueValue' => 1,
                    'falseValue' => 0,
                ],
                '<input type="hidden" name="test" value="0"><input class="&quot; onclick=&quot;alert(&#039;xss&#039;)&quot; data-param=&quot;" data--onclick--alert--xss----data-param="xss" name="test" value="1" type="checkbox">',
            ],
            'checked input' => [
                [
                    'name' => 'test',
                    'value' => 1,
                    'trueValue' => 1,
                    'falseValue' => 0,
                ],
                '<input type="hidden" name="test" value="0"><input name="test" value="1" type="checkbox" checked="checked">',
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
        $type = '\\marvin255\\serviform\\fields\\Checkbox';

        return FactoryFields::initElement($type, $options);
    }
}
