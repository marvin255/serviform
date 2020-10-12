<?php

declare(strict_types=1);

namespace Marvin255\Serviform\ParameterBag;

use InvalidArgumentException;

/**
 * Object that stores parameters in internal array.
 */
class ParameterBagArray implements ParameterBag
{
    /**
     * @var array<string, string>
     */
    private array $params = [];

    /**
     * @param array<string, string> $params
     */
    public function __construct(array $params = [])
    {
        foreach ($params as $name => $value) {
            $this->set($name, $value);
        }
    }

    /**
     * @inheritDoc
     */
    public function set(string $name, string $value): self
    {
        $this->params[$this->unifyParamName($name)] = $value;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function get(string $name): string
    {
        $unifiedName = $this->unifyParamName($name);

        if (!isset($this->params[$unifiedName])) {
            $message = sprintf("Parameter with name '%s' not found.", $name);
            throw new InvalidArgumentException($message);
        }

        return $this->params[$unifiedName];
    }

    /**
     * @inheritDoc
     */
    public function has(string $name): bool
    {
        $unifiedName = $this->unifyParamName($name);

        return isset($this->params[$unifiedName]);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return $this->params;
    }

    /**
     * Unifies parameter's name.
     *
     * @param string $name
     *
     * @return string
     */
    private function unifyParamName(string $name): string
    {
        return strtolower(trim($name));
    }
}
