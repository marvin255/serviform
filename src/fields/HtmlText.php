<?php

namespace marvin255\serviform\fields;

use marvin255\serviform\abstracts\Field;
use marvin255\serviform\helpers\Html;

/**
 * Html text class.
 */
class HtmlText extends Field
{
    /**
     * @return string
     */
    protected function renderInternal()
    {
        $options = $this->getAttributes();

        return Html::createTag(
            'div',
            $options,
            $this->getAllowHtmlContent()
                ? $this->getValue()
                : Html::clearAttributeValue($this->getValue())
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
