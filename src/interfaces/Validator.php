<?php

namespace marvin255\serviform\interfaces;

use JsonSerializable;

/**
 * Interface for validators objects.
 */
interface Validator extends JsonSerializable
{
    /**
     * @return bool
     */
    public function validate(array $elements = null);

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
    public function setElementsToValidate(array $elements);

    /**
     * @return array
     */
    public function getElementsToValidate();
}
