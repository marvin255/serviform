<?php

namespace marvin255\serviform\abstracts;

use marvin255\serviform\interfaces\Validator as IValidator;
use marvin255\serviform\traits\Validator as TValidator;

/**
 * An abstract class for base field.
 */
abstract class Validator implements IValidator
{
    use TValidator;
}
