<?php

namespace marvin255\serviform\helpers;

use marvin255\serviform\traits\Factory;

/**
 * Factory for fields.
 */
class FactoryFields
{
    use Factory;

    /**
     * @return array
     */
    protected static function loadDefaultDescriptions()
    {
        return [
            'button' => ['type' => '\\marvin255\\serviform\\fields\\Button'],
            'checkbox' => ['type' => '\\marvin255\\serviform\\fields\\Checkbox'],
            'file' => ['type' => '\\marvin255\\serviform\\fields\\File'],
            'form' => [
                'type' => '\\marvin255\\serviform\\fields\\Form',
                'template' => realpath(__DIR__ . '/../../views/bootstrap_3.php'),
            ],
            'formGrouped' => ['type' => '\\marvin255\\serviform\\fields\\FormGrouped'],
            'htmlText' => ['type' => '\\marvin255\\serviform\\fields\\HtmlText'],
            'input' => ['type' => '\\marvin255\\serviform\\fields\\Input'],
            'multiple' => ['type' => '\\marvin255\\serviform\\fields\\Multiple'],
            'radioList' => ['type' => '\\marvin255\\serviform\\fields\\RadioList'],
            'select' => ['type' => '\\marvin255\\serviform\\fields\\Select'],
            'textarea' => ['type' => '\\marvin255\\serviform\\fields\\Textarea'],
        ];
    }

    /**
     * @param string $class
     *
     * @return string
     */
    protected static function checkClass($class)
    {
        return is_subclass_of($class, '\\marvin255\\serviform\\interfaces\\Field');
    }

    /**
     * @param array $options
     *
     * @return options
     */
    protected static function checkOptions(array $options)
    {
        if (isset($options['value'])) {
            $value = $options['value'];
            unset($options['value']);
            $options['value'] = $value;
        }

        return $options;
    }
}
