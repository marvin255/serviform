<?php

namespace marvin255\serviform\validators;

use marvin255\serviform\abstracts\Validator;

/**
 * Regular expression validator class.
 */
class Regexp extends Validator
{
    /**
     * @var array
     */
    protected $patterns = [
        'email' => '/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/',
        'url' => '/^https?:\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(?::\d{1,5})?(?:$|[?\/#])/i',
        'ipv4' => '/^(?:(?:2(?:[0-4][0-9]|5[0-5])|[0-1]?[0-9]?[0-9])\.){3}(?:(?:2([0-4][0-9]|5[0-5])|[0-1]?[0-9]?[0-9]))$/',
        'ipv6' => '/^(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))$/',
    ];

    /**
     * @param mixed                 $value
     * @param \serviform\IValidator $element
     *
     * @return bool
     */
    protected function vaidateValue($value, $element)
    {
        if (isset($this->patterns[$this->getRegexp()])) {
            return (bool) preg_match($this->patterns[$this->getRegexp()], $value);
        } elseif ($this->getRegexp()) {
            return (bool) preg_match('/^'.$this->getRegexp().'$/'.$this->getModifiers(), $value);
        } else {
            return false;
        }
    }

    /**
     * @var string
     */
    protected $regexp = null;

    /**
     * @param string $value
     *
     * @return \marvin255\serviform\validators\Regexp
     */
    public function setRegexp($value)
    {
        $this->regexp = trim($value, ' /#$^');

        return $this;
    }

    /**
     * @return string
     */
    public function getRegexp()
    {
        return $this->regexp;
    }

    /**
     * @var string
     */
    protected $modifiers = 'i';

    /**
     * @param string $value
     *
     * @return \marvin255\serviform\validators\Regexp
     */
    public function setModifiers($value)
    {
        $this->modifiers = trim($value, ' /#$^');

        return $this;
    }

    /**
     * @return string
     */
    public function getModifiers()
    {
        return $this->modifiers;
    }
}
