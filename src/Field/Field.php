<?php

declare(strict_types=1);

namespace Marvin255\Serviform\Field;

use InvalidArgumentException;
use Marvin255\Serviform\ParameterBag\ParameterBag;

/**
 * Interface for object that represents single form input element.
 *
 * @template TValue
 */
interface Field
{
    /**
     * Sets link to parent element of this field.
     *
     * @param Field|null $parent
     *
     * @return self
     */
    public function setParent(?Field $parent): self;

    /**
     * Returns link to parent element of this field.
     *
     * @return Field|null
     */
    public function getParent(): ?Field;

    /**
     * Sets name of current field.
     *
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self;

    /**
     * Returns name of current field.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Sets label for current field.
     *
     * @return self
     */
    public function setLabel(string $label): self;

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
     * @param TValue $value
     *
     * @return self
     *
     * @throws InvalidArgumentException
     */
    public function setValue($value): self;

    /**
     * Returns field value.
     *
     * @return TValue
     */
    public function getValue();
}
