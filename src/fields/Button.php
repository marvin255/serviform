<?php

namespace marvin255\serviform\fields;

use marvin255\serviform\abstracts\Field;
use marvin255\serviform\helpers\Html;

/**
 * Button class.
 */
class Button extends Field
{
    /**
     * @return string
     */
    protected function renderInternal()
    {
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
