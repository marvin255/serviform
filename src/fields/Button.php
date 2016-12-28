<?php

namespace marvin255\serviform\fields;

use marvin255\serviform\helpers\Html;
use marvin255\serviform\interfaces\Field as IField;
use marvin255\serviform\traits\Field as TField;

/**
 * Button class.
 */
class Button implements IField
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

        $options = $this->getAttributes();
        $options['name'] = $this->getNameChainString();
        $value = $this->getValue();
        if ($value !== null) {
            $options['value'] = $value;
        }

        return Html::createTag(
            'button',
            $options,
            $this->getAllowHtmlContent()
                ? $this->getLabel()
                : Html::clearAttributeValue($this->getLabel())
        );
    }

    /**
     * @var bool
     */
    protected $allowHtmlContent = false;

    /**
     * @param bool $allowHtmlContent
     *
     * @return \marvin255\serviform\fields\Button
     */
    public function setAllowHtmlContent($allowHtmlContent)
    {
        $this->allowHtmlContent = (bool) $allowHtmlContent;

        return $this;
    }

    /**
     * @return bool
     */
    public function getAllowHtmlContent()
    {
        return $this->allowHtmlContent;
    }
}
