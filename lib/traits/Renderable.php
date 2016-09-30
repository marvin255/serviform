<?php

namespace serviform\traits;

/**
 * Trait for items that can render themselves.
 */
trait Renderable
{
    /**
     * @var mixed
     */
    protected $_template = null;

    /**
     * @return string
     */
    public function renderTemplate()
    {
        $return = null;
        $template = $this->getTemplate();
        if ($template !== null) {
            if (is_callable($template)) {
                $return = call_user_func($template, $this);
            } elseif (file_exists($template)) {
                ob_start();
                ob_implicit_flush(false);
                require $template;
                $return = ob_get_clean();
            }
        }

        return $return;
    }

    /**
     * @param mixed $template
     *
     * @return mixed
     */
    public function setTemplate($template)
    {
        $this->_template = $template;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->_template;
    }
}
