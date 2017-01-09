<?php

namespace marvin255\serviform\abstracts;

use marvin255\serviform\interfaces\Field as IField;
use marvin255\serviform\traits\Field as TField;

/**
 * An abstract class for base field.
 */
abstract class Field implements IField
{
    use TField;
}
