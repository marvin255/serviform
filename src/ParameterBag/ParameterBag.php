<?php

declare(strict_types=1);

namespace Marvin255\Serviform\ParameterBag;

use InvalidArgumentException;

/**
 * Interface for objects that storage list of parameters by their names.
 */
interface ParameterBag
{
    /**
     * Sets named parameter's value.
     *
     * @param string $name
     * @param string $value
     *
     * @return self
     */
    public function set(string $name, string $value): self;

    /**
     * Returns named parameter's value.
     *
     * @param string $name
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public function get(string $name): string;

    /**
     * Returns true if parameter with name set.
     *
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool;

    /**
     * Returns array representation of all parameters from bag.
     *
     * @return array<string, string>
     */
    public function toArray(): array;
}
