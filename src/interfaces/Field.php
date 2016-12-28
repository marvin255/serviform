<?php

namespace marvin255\serviform\interfaces;

use JsonSerializable;

/**
 * Interface for input field.
 */
interface Field extends JsonSerializable
{
    /**
     * @param mixed $template
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    public function setTemplate($template);

    /**
     * @return string
     */
    public function getTemplate();

    /**
     * @return string
     */
    public function getInput();

    /**
     * @param \marvin255\serviform\interfaces\Field $parent
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    public function setParent(\marvin255\serviform\interfaces\Field $parent);

    /**
     * @return \marvin255\serviform\interfaces\Field
     */
    public function getParent();

    /**
     * @param mixed $value
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    public function setValue($value);

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    public function setAttribute($name, $value);

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    public function addToAttribute($name, $value);

    /**
     * @param array $attributes
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    public function setAttributes(array $attributes);

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getAttribute($name);

    /**
     * @return array
     */
    public function getAttributes();

    /**
     * @param string $name
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function getFullName();

    /**
     * @return string
     */
    public function getNameChainString();

    /**
     * @param string $error
     *
     * @return \marvin255\serviform\interfaces\HasErrors
     */
    public function addError($error);

    /**
     * @return array
     */
    public function getErrors();

    /**
     * @param string $error
     */
    public function clearErrors();

    /**
     * @param string $label
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    public function setLabel($label);

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @return string
     */
    public function __toString();
}
