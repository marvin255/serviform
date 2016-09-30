<?php

namespace serviform;

/**
 * Base validator class.
 */
abstract class ValidatorBase implements IValidator
{
    use \serviform\traits\Configurable;

    /**
     * @var string
     */
    public $message = 'Field "#label#" is invalid';
    /**
     * @var bool
     */
    public $skipOnError = false;
    /**
     * @var bool
     */
    public $skipOnEmpty = false;
    /**
     * @var callable
     */
    public $when = null;
    /**
     * @var \serviform\IValidateable
     */
    protected $_parent = null;
    /**
     * @var array
     */
    protected $_elements = null;

    /**
     * @param mixed $value
     *
     * @return bool
     */
    abstract protected function vaidateValue($value, $element);

    /**
     * @return bool
     */
    public function validate(array $elements = null)
    {
        $return = true;
        $arElements = $this->getFieldsToValidate($elements);
        foreach ($arElements as $element) {
            if ($this->isValidationNeeded($element, $elements)) {
                $res = $this->validateElement($element);
                if ($res === false) {
                    $return = false;
                }
            }
        }

        return $return;
    }

    /**
     * @param array $fieldsToValidate
     */
    protected function getFieldsToValidate(array $fieldsToValidate = null)
    {
        $return = [];
        $elementNames = $this->getElements();
        $parent = $this->getParent();
        foreach ($elementNames as $elementName) {
            if (
                !empty($fieldsToValidate)
                && !in_array($elementName, $fieldsToValidate)
            ) {
                continue;
            }
            $element = $parent->getElement($elementName);
            if ($element === null) {
                throw new Exception('Wrong validated field name');
            }
            $return[] = $element;
        }

        return $return;
    }

    /**
     * @param \serviform\IElement $element
     *
     * @return bool
     */
    protected function isValidationNeeded(\serviform\IElement $element, $elements)
    {
        return
            (
                !$this->skipOnError
                || !$element->getErrors()
            ) && (
                !$this->skipOnEmpty
                || !$this->isEmpty($element->getValue())
            ) && (
                !is_callable($this->when)
                || call_user_func_array($this->when, [$this, $element])
            ) && (
                empty($elements)
                || in_array($element->getName(), $elements)
            );
    }

    /**
     * @param \serviform\IElement $element
     *
     * @return bool
     */
    protected function validateElement(\serviform\IElement $element)
    {
        $value = $element->getValue();
        $res = $this->vaidateValue($value, $element);
        if ($res === false) {
            $element->addError($this->createErrorMessage($element));
        }

        return $res;
    }

    /**
     * @param \serviform\IElement $element
     *
     * @return string
     */
    protected function createErrorMessage(\serviform\IElement $element)
    {
        $replaces = [
            '#label#' => $element->getLabel(),
            '#value#' => $element->getValue(),
            '#name#' => $element->getName(),
        ];

        return str_replace(
            array_keys($replaces),
            array_values($replaces),
            $this->message
        );
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    protected function isEmpty($value)
    {
        return $value === null || $value === '' || $value === [];
    }

    /**
     * @param \serviform\IValidateable $parent
     */
    public function setParent(\serviform\IValidateable $parent)
    {
        $this->_parent = $parent;
    }

    /**
     * @return \serviform\IValidateable
     */
    public function getParent()
    {
        return $this->_parent;
    }

    /**
     * @param array $elements
     */
    public function setElements(array $elements)
    {
        $this->_elements = $elements;
    }

    /**
     * @return array
     */
    public function getElements()
    {
        return $this->_elements;
    }
}
