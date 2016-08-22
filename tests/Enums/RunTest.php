<?php
/**
 * Enums - php 7 enums
 *
 * @author Vijay Mahrra <vijay@yoyo.org>
 */

namespace Enums;

use vijinho\Enums\Enum;

class RunTest extends TestCase
{
    /**
     * @covers Enums\Enum::caseSensitive
     */
    public function testCaseSensitive()
    {
        $e = static::$enum;
    }
}
