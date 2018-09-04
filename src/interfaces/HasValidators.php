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
    public function validate(array $toValidate = []);

    /**
     * @param array $rules
     *
     * @return \marvin255\serviform\interfaces\HasValidators
     */
    public function setRules(array $rules);

    /**
     * @return array
     */
    public function getRules();
}
