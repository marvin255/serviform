<?php

namespace marvin255\serviform\abstracts;

use marvin255\serviform\interfaces\HasValidators;
use InvalidArgumentException;

/**
 * An abstract class for field that has list of values.
 */
abstract class FieldHasValidators extends FieldHasChildren implements HasValidators
{
    /**
     * @param array $elements
     *
     * @return bool
     */
    public function validate(array $toValidate = array())
    {
        $return = true;
        $validators = $this->getValidatorsForFields($toValidate);
        foreach ($validators as $validator) {
            $res = $validator->validate($toValidate);
            if ($res === false && $return === true) {
                $return = false;
            }
        }
        $elements = $this->getElements();
        foreach ($elements as $el) {
            if (
                (!empty($toValidate) && !in_array($el->getName(), $toValidate))
                || !($el instanceof \marvin255\serviform\interfaces\HasValidators)
            ) {
                continue;
            }
            $res = $el->validate();
            if ($res === false && $return === true) {
                $return = false;
            }
        }

        return $return;
    }

    /**
     * @var array
     */
    protected $validators = [];

    /**
     * @param array $elements
     *
     * @return array
     */
    protected function getValidatorsForFields(array $elements = array())
    {
        $return = [];
        $rules = $this->getRules();
        foreach ($rules as $key => $rule) {
            if (!empty($elements) && !array_intersect($elements, $rule[0])) {
                continue;
            }
            if (!array_key_exists($key, $this->elements)) {
                $options = $rule;
                $options['parent'] = $this;
                $type = $options[1];
                unset($options[1]);
                $this->validators[$key] = $this->createValidator($type, $options);
            }
            $return[] = $this->validators[$key];
        }

        return $return;
    }

    /**
     * @param string $type
     * @param array  $options
     *
     * @return \marvin255\serviform\interfaces\Validator
     */
    protected function createValidator($type, array $options)
    {
        return \marvin255\serviform\helpers\FactoryValidators::initElement(
            $type,
            $options
        );
    }

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @param array $rules
     *
     * @throws \InvalidArgumentException
     *
     * @return \marvin255\serviform\interfaces\HasValidators
     */
    public function setRules(array $rules)
    {
        $this->validators = [];
        foreach ($rules as $rule) {
            if (is_array($rule[0]) && is_string($rule[1])) {
                continue;
            }
            throw new InvalidArgumentException('Bad validation rule');
        }
        $this->rules = $rules;

        return $this;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }
}
