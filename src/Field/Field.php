<?php

declare(strict_types=1);

namespace Marvin255\Serviform\Field;

use InvalidArgumentException;
use Marvin255\Serviform\ParameterBag\ParameterBag;

/**
 * Interface for object that represents single form input element.
 */
interface Field
{
    /**
     * Returns link to parent element of this field.
     *
     * @return Field|null
     */
    public function getParent(): ?Field;

    /**
     * Returns name of current field.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Returns label for current field.
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Returns attributes bag for current field.
     *
     * @return ParameterBag
     */
    public function getAttributes(): ParameterBag;

    /**
     * Returns errors bag for current field.
     *
     * @return ParameterBag
     */
    public function getErrors(): ParameterBag;

    /**
     * Sets field value.
     *
     * @param mixed $value
     *
     * @return self
     *
     * @throws InvalidArgumentException
     */
    public function setValue($value): self;

    /**
     * Returns field value.
     *
     * @return mixed
     */
    public function getValue();
}
