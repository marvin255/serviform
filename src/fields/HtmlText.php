<?php

namespace marvin255\serviform\fields;

use marvin255\serviform\helpers\Html;
use marvin255\serviform\interfaces\Field as IField;
use marvin255\serviform\traits\Field as TField;

/**
 * Html text class.
 */
class HtmlText implements IField
{
    use TField;

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
