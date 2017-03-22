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
        foreach ($this->validators as $key => $validator) {
            if (!empty($elements) && !array_intersect($elements, $validator->getElements())) {
                continue;
            }
            $return[$key] = $validator;
        }
        $rules = $this->getRules();
        foreach ($rules as $key => $rule) {
            if (isset($return[$key]) || !empty($elements) && !array_intersect($elements, $rule[0])) {
                continue;
            }
            $return[$key] = $this->getValidator($key);
        }

        return array_values($return);
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
        } elseif (is_array($element) && !empty($element['type'])) {
            $element['parent'] = $this;
            $type = $element['type'];
            unset($element['type']);
            $validator = $this->createValidator($type, $element);
        } elseif ($element instanceof \marvin255\serviform\interfaces\Validator) {
            $element->setParent($this);
            $validator = $element;
        } else {
            throw new InvalidArgumentException('Wrong child type for rule: ' . $name);
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
        if (!isset($this->validators[$name])) {
            $rules = $this->getRules();
            if (!isset($rules[$name])) {
                throw new InvalidArgumentException('Can not find rule with name: ' . $name);
            } else {
                $options = $rules[$name];
                $options['elements'] = $options[0];
                $options['type'] = $options[1];
                unset($options[0], $options[1]);
                $this->setValidator($name, $options);
            }
        }
        return $this->validators[$name];
    }
}
