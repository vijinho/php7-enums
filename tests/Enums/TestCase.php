<?php
/**
 * Enums - php 7 enums
 *
 * @author Vijay Mahrra <vijay@yoyo.org>
 */

namespace Enums;

use vijinho\Enums\Enum;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected static $enum;

    public static function setUpBeforeClass()
    {
        self::$enum = new Enum;
    }

    public static function tearDownAfterClass()
    {
        self::$enum->reset();
        self::$enum = null;
    }

    /**
     * @param  string    $message
     * @return Exception
     */
    protected function getException($message = null)
    {
        // HHVM does not support mocking exceptions
        // Since we do not use any additional features of Mockery for exceptions,
        // we can just use native Exceptions instead.
        return new \Exception($message);
    }
}
