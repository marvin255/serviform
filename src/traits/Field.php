<?php

namespace marvin255\serviform\traits;

/**
 * Trait for fields classes.
 */
trait Field
{
    /**
     * @return string
     */
    public function getInput()
    {
        return $this->renderTemplate();
    }

    /**
     * @var string
     */
    protected $template = null;

    /**
     * @param mixed $template
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    public function setTemplate($template)
    {
        $this->template = is_string($template) ? trim($template) : $template;

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return string|null
     */
    protected function renderTemplate()
    {
        $return = null;
        $templatePath = $this->getTemplate();
        if ($templatePath !== null) {
            if (is_callable($templatePath)) {
                $return = call_user_func($templatePath, $this);
            } elseif (file_exists($templatePath)) {
                ob_start();
                ob_implicit_flush(false);
                require $templatePath;
                $return = ob_get_clean();
            }
        }

        return $return;
    }

    /**
     * @var \marvin255\serviform\interfaces\Field
     */
    protected $parent = null;

    /**
     * @param \marvin255\serviform\interfaces\Field $parent
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    public function setParent(\marvin255\serviform\interfaces\Field $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return \marvin255\serviform\interfaces\Field
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @var mixed
     */
    protected $value = null;

    /**
     * @param mixed $value
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    public function addToAttribute($name, $value)
    {
        $attr = $this->getAttribute($name);
        $this->setAttribute($name, $attr.$value);

        return $this;
    }

    /**
     * @param array $attributes
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = [];
        foreach ($attributes as $name => $value) {
            $this->setAttribute($name, $value);
        }

        return $this;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getAttribute($name)
    {
        $attributes = $this->getAttributes();

        return isset($attributes[$name]) ? $attributes[$name] : null;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @param string $name
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    public function setName($name)
    {
        $this->name = trim($name);

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getFullName()
    {
        $return = [];
        $parent = $this->getParent();
        if ($parent) {
            $return = $parent->getFullName();
        }
        $name = $this->getName();
        if ($name !== '') {
            $return[] = $name;
        }

        return $return;
    }

    /**
     * @return string
     */
    public function getNameChainString()
    {
        $names = $this->getFullName();
        $return = array_shift($names);
        if (!empty($names)) {
            $return .= '['.implode('][', $names).']';
        }

        return $return;
    }

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @param string $error
     *
     * @return \marvin255\serviform\interfaces\HasErrors
     */
    public function addError($error)
    {
        $this->errors[] = $error;

        return $this;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param string $error
     *
     * @return \marvin255\serviform\interfaces\HasErrors
     */
    public function clearErrors()
    {
        $this->errors = [];

        return $this;
    }

    /**
     * @var string
     */
    protected $label = '';

    /**
     * @param string $label
     *
     * @return \marvin255\serviform\interfaces\Field
     */
    public function setLabel($label)
    {
        $this->label = trim($label);

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getInput();
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'type' => str_replace(
                [
                    '\\marvin255\\serviform\\fields\\',
                    'marvin255\\serviform\\fields\\',
                ],
                '',
                get_class($this)
            ),
            'attributes' => $this->getAttributes(),
            'name' => $this->getName(),
            'fullName' => $this->getFullName(),
            'errors' => $this->getErrors(),
            'value' => $this->getValue(),
            'label' => $this->getLabel(),
        ];
    }
}
