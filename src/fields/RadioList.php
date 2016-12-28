<?php

namespace marvin255\serviform\fields;

use marvin255\serviform\helpers\Html;
use marvin255\serviform\interfaces\Field as IField;
use marvin255\serviform\traits\HasList as TField;

/**
 * Radio list class.
 */
class RadioList implements IField
{
    use TField;

    /**
     * @return string
     */
    public function getInput()
    {
        $input = $this->renderTemplate();
        if ($input !== null) {
            return $input;
        }

        $list = $this->getList();
        $value = $this->getValue();
        $options = [
            'name' => $this->getNameChainString(),
        ];
        $isMultiple = $this->getIsMultiple();
        $content = '';
        $listItemsOptions = $this->getListItemsOptions();
        foreach ($list as $optionValue => $optionContent) {
            $optionOptions = isset($listItemsOptions[$optionValue]) && is_array($listItemsOptions[$optionValue])
                ? array_merge($options, $listItemsOptions[$optionValue])
                : $options;
            $optionOptions['value'] = $optionValue;
            $optionOptions['type'] = $isMultiple ? 'checkbox' : 'radio';
            $optionOptions['id'] = Html::clearAttributeKey($options['name'].'-'.$optionValue);
            if (
                $value !== null
                && (
                    (!$isMultiple && $optionValue == $value)
                    || ($isMultiple && is_array($value) && in_array($optionValue, $value))
                )
            ) {
                $optionOptions['checked'] = 'checked';
            }
            $option = Html::createTag('input', $optionOptions, false);
            $labelOptions = $this->getLabelOptions();
            $labelOptions['for'] = $optionOptions['id'];
            $content .= Html::createTag(
                'label',
                $labelOptions,
                $option.Html::clearAttributeValue($optionContent)
            );
        }

        return Html::createTag('div', $this->getAttributes(), $content);
    }

    /**
     * @var array
     */
    protected $labelOptions = [];

    /**
     * @param array $list
     */
    public function setLabelOptions(array $list)
    {
        $this->labelOptions = $list;

        return $this;
    }

    /**
     * @return array
     */
    public function getLabelOptions()
    {
        return $this->labelOptions;
    }
}
