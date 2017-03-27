<?php

namespace marvin255\serviform\traits;

use InvalidArgumentException;

/**
 * Trait for validator classes.
 */
trait Validator
{
    /**
     * @param array $elements
     *
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
     *
     * @throws \InvalidArgumentException
     *
     * @return array
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
                throw new InvalidArgumentException('Wrong validated field name: '.$elementName);
            }
            $return[] = $element;
        }

        return $return;
    }

    /**
     * @param \marvin255\serviform\interfaces\Field $element
     * @param array                                 $elements
     *
     * @return bool
     */
    protected function isValidationNeeded(\marvin255\serviform\interfaces\Field $element, $elements)
    {
        return
            (
                !$this->getSkipOnError()
                || !$element->getErrors()
            ) && (
                !$this->getSkipOnEmpty()
                || !$this->isEmpty($element->getValue())
            ) && (
                !is_callable($this->getWhen())
                || call_user_func_array($this->getWhen(), [$this, $element])
            ) && (
                empty($elements) || in_array($element->getName(), $elements)
            );
    }

    /**
     * @param \marvin255\serviform\interfaces\Field $element
     *
     * @return bool
     */
    protected function validateElement(\marvin255\serviform\interfaces\Field $element)
    {
        $value = $element->getValue();
        if ($this->getArrayValue()) {
            if (!is_array($value)) {
                throw new InvalidArgumentException('Value must ba an array for element: ' . $element->getName());
            }
            $res = true;
            foreach ($value as $item) {
                if ($this->vaidateValue($item, $element)) {
                    continue;
                }
                $res = false;
                break;
            }
        } else {
            $res = $this->vaidateValue($value, $element);
        }
        if ($res === false) {
            $element->addError($this->createErrorMessage($element));
        }

        return $res;
    }

    /**
     * @param \marvin255\serviform\interfaces\Field $element
     *
     * @return string
     */
    protected function createErrorMessage(\marvin255\serviform\interfaces\Field $element)
    {
        $value = $element->getValue();
        $replaces = [
            '#label#' => $element->getLabel(),
            '#value#' => is_array($value) ? '' : $value,
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
     * @var string
     */
    protected $message = 'Field "#label#" is invalid';

    /**
     * @param string $message
     *
     * @return \marvin255\serviform\interfaces\Validator
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @var \marvin255\serviform\interfaces\HasValidators
     */
    protected $parent = null;

    /**
     * @param \marvin255\serviform\interfaces\HasValidators $parent
     *
     * @return \marvin255\serviform\interfaces\Validator
     */
    public function setParent(\marvin255\serviform\interfaces\HasValidators $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return \marvin255\serviform\interfaces\HasValidators
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @var array
     */
    protected $elements = [];

    /**
     * @param array $elements
     *
     * @return \marvin255\serviform\interfaces\Validator
     */
    public function setElements(array $elements)
    {
        $this->elements = $elements;

        return $this;
    }

    /**
     * @return array
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * @var bool
     */
    protected $skipOnError = false;

    /**
     * @param bool $value
     *
     * @return \marvin255\serviform\interfaces\Validator
     */
    public function setSkipOnError($value)
    {
        $this->skipOnError = (bool) $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getSkipOnError()
    {
        return $this->skipOnError;
    }

    /**
     * @var bool
     */
    protected $skipOnEmpty = false;

    /**
     * @param bool $value
     *
     * @return \marvin255\serviform\interfaces\Validator
     */
    public function setSkipOnEmpty($value)
    {
        $this->skipOnEmpty = (bool) $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getSkipOnEmpty()
    {
        return $this->skipOnEmpty;
    }

    /**
     * @var callable
     */
    protected $when = null;

    /**
     * @param bool $value
     *
     * @throws \InvalidArgumentException
     *
     * @return \marvin255\serviform\interfaces\Validator
     */
    public function setWhen($value)
    {
        if (!is_callable($value) && $value !== null) {
            throw new InvalidArgumentException('When parameter must be callable type');
        }
        $this->when = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getWhen()
    {
        return $this->when;
    }

    /**
     * @var bool
     */
    protected $arrayValue = false;

    /**
     * @param bool $value
     *
     * @throws \InvalidArgumentException
     *
     * @return \marvin255\serviform\interfaces\Validator
     */
    public function setArrayValue($value)
    {
        $this->arrayValue = (bool) $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getArrayValue()
    {
        return $this->arrayValue;
    }
}
