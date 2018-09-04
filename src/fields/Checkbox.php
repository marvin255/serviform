<?php

namespace marvin255\serviform\fields;

use marvin255\serviform\helpers\Html;
use marvin255\serviform\abstracts\Field;

/**
 * Checkbox class.
 */
class Checkbox extends Field
{
    /**
     * @return string
     */
    protected function renderInternal()
    {
        $value = $this->getValue();
        $options = $this->getAttributes();
        $options['name'] = $this->getNameChainString();
        $options['value'] = $this->getTrueValue();
        $options['type'] = 'checkbox';
        if ($value == $this->getTrueValue()) {
            $options['checked'] = 'checked';
        }
        $option = Html::createTag('input', $options, false);
        $disableOptions = [
            'type' => 'hidden',
            'name' => $options['name'],
            'value' => $this->getFalseValue(),
        ];
        $disableOption = Html::createTag('input', $disableOptions, false);

        return $disableOption . $option;
    }

    /**
     * @var bool
     */
    protected $trueValue = '1';

    /**
     * @param string $value
     *
     * @return \marvin255\serviform\fields\Checkbox
     */
    public function setTrueValue($value)
    {
        $this->trueValue = trim($value);
    }

    /**
     * @return string
     */
    public function getTrueValue()
    {
        return $this->trueValue;
    }

    /**
     * @var bool
     */
    protected $falseValue = '';

    /**
     * @param string $value
     *
     * @return \marvin255\serviform\fields\Checkbox
     */
    public function setFalseValue($value)
    {
        $this->falseValue = trim($value);
    }

    /**
     * @return string
     */
    public function getFalseValue()
    {
        return $this->falseValue;
    }
}
