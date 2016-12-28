<?php

namespace marvin255\serviform\interfaces;

/**
 * Interface for objects that have validators handler.
 */
interface HasValidators
{
    /**
     * @param array $toValidate
     *
     * @return bool
     */
    public function validate(array $toValidate = null);
}
