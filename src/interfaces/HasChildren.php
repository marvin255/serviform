<?php

namespace marvin255\serviform\interfaces;

/**
 * Interface for objects that have children handler.
 */
interface HasChildren
{
    /**
     * @param array $elements
     *
     * @return \marvin255\serviform\interfaces\HasChildren
     */
    public function setElements(array $elements);

    /**
     * @param string $name
     * @param mixed  $element
     *
     * @return \marvin255\serviform\interfaces\HasChildren
     */
    public function setElement($name, $element);

    /**
     * @return array
     */
    public function getElements();

    /**
     * @param string $name
     *
     * @return \marvin255\serviform\interfaces\Field|null
     */
    public function getElement($name);

    /**
     * @param string $name
     *
     * @return \marvin255\serviform\interfaces\HasChildren
     */
    public function unsetElement($name);
}
