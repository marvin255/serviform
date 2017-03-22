<?php

namespace marvin255\serviform\validators;

use marvin255\serviform\abstracts\Validator;

/**
 * File validator class.
 */
class File extends Validator
{
    /**
     * @param mixed                 $value
     * @param \serviform\IValidator $element
     *
     * @return bool
     */
    protected function vaidateValue($value, $element)
    {
        $return = !empty($value['tmp_name']) && !empty($value['name']) && empty($value['error']);
        if ($return && $this->getExtensions()) {
            $ext = pathinfo($value['name'], PATHINFO_EXTENSION);
            $return = in_array($ext, $this->getExtensions());
        }
        if ($return && $this->getMimes()) {
            $mime = extension_loaded('fileinfo') ? mime_content_type($value['tmp_name']) : $value['type'];
            $return = in_array($mime, $this->getMimes());
        }
        if ($return && $this->getMaxSize()) {
            $size = filesize($value['tmp_name']);
            $return = $size <= $this->getMaxSize();
        }

        return $return;
    }

    /**
     * @var array
     */
    protected $extensions = null;

    /**
     * @param array $value
     *
     * @return \marvin255\serviform\validators\Filter
     */
    public function setExtensions(array $value)
    {
        $this->extensions = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * @var array
     */
    protected $mimes = null;

    /**
     * @param array $value
     *
     * @return \marvin255\serviform\validators\Filter
     */
    public function setMimes(array $value)
    {
        $this->mimes = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getMimes()
    {
        return $this->mimes;
    }

    /**
     * @var int
     */
    protected $maxSize = null;

    /**
     * @param int $value
     *
     * @return \marvin255\serviform\validators\Filter
     */
    public function setMaxSize($value)
    {
        $this->maxSize = (int) $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxSize()
    {
        return $this->maxSize;
    }
}
