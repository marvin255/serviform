<?php

namespace marvin255\serviform\tests;

use PHPUnit\Framework\TestCase;

/**
 * Parent class for all tests in suite.
 */
abstract class BaseTestCase extends TestCase
{
    /**
     * @inheritDoc
     */
    public function setExpectedException($exception, $message = '', $code = null)
    {
        if (method_exists('\PHPUnit\Framework\TestCase', 'setExpectedException')) {
            parent::setExpectedException($exception);
        } else {
            $this->expectException($exception);
        }
    }
}
