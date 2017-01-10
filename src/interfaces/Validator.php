<?php

namespace marvin255\serviform\interfaces;

use JsonSerializable;

/**
 * Interface for validators objects.
 */
interface Validator
{
    /**
     * @param array $elements
     *
     * @return bool
     */
    public function validate(array $elements = null);

    /**
     * @param string $message
     *
     * @return \marvin255\serviform\interfaces\Validator
     */
    public function setMessage($message);

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param \marvin255\serviform\interfaces\HasValidators $parent
     *
     * @return \marvin255\serviform\interfaces\Validator
     */
    public function setParent(\marvin255\serviform\interfaces\HasValidators $parent);

    /**
     * @return \marvin255\serviform\interfaces\HasValidators
     */
    public function getParent();

    /**
     * @param array $elements
     *
     * @return \marvin255\serviform\interfaces\Validator
     */
    public function setElements(array $elements);

    /**
     * @return array
     */
    public function getElements();

    /**
     * @param bool $value
     *
     * @return \marvin255\serviform\interfaces\Validator
     */
    public function setSkipOnError($value);

    /**
     * @return string
     */
    public function getSkipOnError();

    /**
     * @param bool $value
     *
     * @return \marvin255\serviform\interfaces\Validator
     */
    public function setSkipOnEmpty($value);

    /**
     * @return string
     */
    public function getSkipOnEmpty();

    /**
     * @param bool $value
     *
     * @throws \InvalidArgumentException
     *
     * @return \marvin255\serviform\interfaces\Validator
     */
    public function setWhen($value);

    /**
     * @return string
     */
    public function getWhen();
}
