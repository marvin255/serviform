<?php

namespace serviform;

/**
 * Validator interface.
 */
interface IValidator
{
    /**
     * @return bool
     */
    public function validate(array $elements = null);

    /**
     * @param \serviform\IValidateable $parent
     */
    public function setParent(\serviform\IValidateable $parent);

    /**
     * @return \serviform\IValidateable
     */
    public function getParent();

    /**
     * @param array $elements
     */
    public function setElements(array $elements);

    /**
     * @return array
     */
    public function getElements();

    /**
     * @param array $options
     */
    public function config(array $options);
}
