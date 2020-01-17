<?php

namespace marvin255\serviform\fields;

use marvin255\serviform\abstracts\FieldHasList;
use marvin255\serviform\helpers\Html;

/**
 * Select class.
 */
class Select extends FieldHasList
{
    /**
     * @return string
     */
    protected function renderInternal()
    {
        $list = $this->getList();
        $prompt = $this->getPrompt();
        if (!empty($prompt)) {
            $oldList = $list;
            $list = ['' => $prompt];
            foreach ($oldList as $key => $val) {
                $list[$key] = $val;
            }
        }
        $value = $this->getValue();
        $options = $this->getAttributes();
        if ($this->getIsMultiple()) {
            $options['multiple'] = 'multiple';
        } else {
            unset($options['multiple']);
        }
        $options['name'] = $this->getNameChainString();
        $isMultiple = $this->getIsMultiple();
        $content = '';
        $listItemsOptions = $this->getListItemsOptions();
        foreach ($list as $optionValue => $optionContent) {
            $optionOptions = isset($listItemsOptions[$optionValue]) && is_array($listItemsOptions[$optionValue])
                ? $listItemsOptions[$optionValue]
                : [];
            $optionOptions['value'] = $optionValue;
            if (
                $optionValue !== ''
                && $value !== null
                && (
                    (!$isMultiple && $optionValue == $value)
                    || ($isMultiple && is_array($value) && in_array($optionValue, $value))
                )
            ) {
                $optionOptions['selected'] = 'selected';
            }
            $content .= Html::createTag(
                'option',
                $optionOptions,
                Html::clearAttributeValue($optionContent)
            );
        }

        return Html::createTag('select', $options, $content);
    }

    /**
     * @var array
     */
    protected $prompt = null;

    /**
     * @param array $prompt
     */
    public function setPrompt($prompt)
    {
        $this->prompt = $prompt;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrompt()
    {
        return $this->prompt;
    }
}
