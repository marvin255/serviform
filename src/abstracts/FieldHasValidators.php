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
            if ($this->getValidator($key) === null) {
                $options = $rule;
                unset($options[0], $options[1]);
                $options['elements'] = $rule[0];
                $options['type'] = $rule[1];
                $options['parent'] = $this;
                $this->setValidator($key, $options);
            }
            $return[] = $this->getValidator($key);
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

    /**
     * @param string $name
     * @param mixed  $element
     *
     * @throws \InvalidArgumentException
     *
     * @return \marvin255\serviform\interfaces\HasValidators
     */
    public function setValidator($name, $element)
    {
        $name = trim($name);
        if ($name === '') {
            throw new InvalidArgumentException('Empty rule name');
        }
        if (is_array($element) && !empty($element['type'])) {
            $element['parent'] = $this;
            $type = $element['type'];
            unset($element['type']);
            $validator = $this->createValidator($type, $element);
        }
        if ($element instanceof \marvin255\serviform\interfaces\Validator) {
            $element->setParent($this);
            $validator = $element;
        }
        if (!isset($validator)) {
            throw new InvalidArgumentException('Wrong type for rule: ' . $name);
        }
        $this->validators[$name] = $validator;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return \marvin255\serviform\interfaces\Validator
     */
    public function getValidator($name)
    {
        return isset($this->validators[$name]) ? $this->validators[$name] : null;
    }
}
