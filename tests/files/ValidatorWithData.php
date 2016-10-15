<?php

class ValidatorWithData extends \serviform\ValidatorBase implements \serviform\IValidatorWithData
{
    protected function vaidateValue($value, $element)
    {
        return true;
    }

    public function setValidatorData()
    {
        $elements = $this->getFieldsToValidate();
        foreach ($elements as $element) {
            $element->setAttribute('ValidatorWithData', 'set');
        }
    }
}
