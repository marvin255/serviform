<?php

namespace marvin255\serviform\traits;

/**
 * Trait for fields that have list.
 */
trait HasList
{
    use Field {
        jsonSerialize as jsonSerializeField;
        getValue as getValueField;
        getNameChainString as getNameChainStringField;
    }

    /**
     * @var array
     */
    protected $list = [];

    /**
     * @param array $list
     *
     * @return \marvin255\serviform\traits\HasList
     */
    public function setList(array $list)
    {
        $this->list = $list;

        return $this;
    }

    /**
     * @return array
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @var array
     */
    protected $listItemsOptions = [];

    /**
     * @param array $list
     *
     * @return \marvin255\serviform\traits\HasList
     */
    public function setListItemsOptions(array $list)
    {
        $this->listItemsOptions = $list;

        return $this;
    }

    /**
     * @return array
     */
    public function getListItemsOptions()
    {
        return $this->listItemsOptions;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        $value = $this->getValueField();
        if ($this->getIsMultiple()) {
            return is_array($value) ? $value : [$value];
        } else {
            return is_array($value) ? reset($value) : $value;
        }
    }

    /**
     * @return string
     */
    public function getNameChainString()
    {
        $return = $this->getNameChainStringField();
        if ($this->getIsMultiple()) {
            $return .= '[]';
        }

        return $return;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $toJson = $this->jsonSerializeField();
        $toJson['list'] = $this->getList();
        $toJson['listItemsOptions'] = $this->getListItemsOptions();
        $toJson['multiple'] = $this->getIsMultiple();

        return $toJson;
    }

    /**
     * @var bool
     */
    protected $isMultiple = false;

    /**
     * @param bool $isMultiple
     *
     * @return \marvin255\serviform\traits\HasList
     */
    public function setIsMultiple($isMultiple)
    {
        $this->isMultiple = (bool) $isMultiple;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsMultiple()
    {
        return $this->isMultiple;
    }
}
