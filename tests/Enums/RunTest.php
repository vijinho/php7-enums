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
     * Test boolean $capitalize setting
     */
    public function testCapitalize()
    {
        $e = static::$enum;
        $e->caseSensitive(true);
        $e->capitalize(true);
        $e->add(['richard' => 'Stallman']);
        $this->assertEquals($e->value('RICHARD'), 'Stallman');
    }

    /**
     * Test boolean $caseSensitive setting
     * @depends testCapitalize
     */
    public function testCaseSensitive()
    {
        $e = static::$enum;
        $e->caseSensitive(true);
        $e->capitalize(false);

        try {
            $this->assertEquals($e->value('richard'), 'Stallman');
        } catch (\Exception $ex) {
            $this->assertEquals(get_class($ex), 'InvalidArgumentException');
        }

        $e->caseSensitive(false);
        $this->assertEquals($e->value('richard'), 'Stallman');
        $this->assertEquals($e->value('RICHARD'), 'Stallman');
    }

    /**
     * @covers \vijinho\Enums\Enum::Count
     */
    public function testCount()
    {
        $e = static::$enum;
        $e->reset();
        $e->add(['one', 'two', 'three']);
        $this->assertEquals($e->count(), 3);
    }

    /**
     * By default this delete() will fail because Enum::$delete = false
     * meaning delete is not allowed
     *
     * @covers \vijinho\Enums\Enum::delete
     * @depends testCount
     */
    public function testDelete()
    {
        $e = static::$enum;
        try {
            $e->delete('one');
        } catch (\Exception $ex) {
            $this->assertEquals(get_class($ex), 'LogicException');
        }
    }

    /**
     * @covers \vijinho\Enums\Enum::fixKeys
     */
    public function testFixKeys()
    {
        $e = static::$enum;
        $e->reset();
        $e->add(['this is bad', 'not_bad']);
        $this->assertTrue(in_array('this_is_bad', $e->keys()));
    }

    /**
     * @covers \vijinho\Enums\Enum::key
     */
    public function testKey()
    {
        $e = static::$enum;
        $e->reset();
        $e->add(['newkey']);
        $this->assertEquals($e->key('newkey'), 'newkey');
        $this->assertEquals($e->count(), 1);
    }

    /**
     * @covers \vijinho\Enums\Enum::keys
     */
    public function testKeys()
    {
        $e = static::$enum;
        $e->reset();
        $e->add(['one', 'two', 'three']);
        $keys = $e->keys();
        $this->assertEquals($e->count(), 3);
        $this->assertTrue(in_array('one', $keys));
        $this->assertTrue(in_array('two', $keys));
        $this->assertTrue(in_array('three', $keys));
    }

    /**
     * @covers \vijinho\Enums\Enum::reset
     * @depends testKeys
     */
    public function testReset()
    {
        $e = static::$enum;
        $this->assertEquals($e->count(), 3);
        $e->reset();
        $this->assertTrue($e->count() == 0);
    }

    /**
     * @covers \vijinho\Enums\Enum::serialize
     */
    public function testSerialize()
    {
        $e = static::$enum;
        $e->reset();
        $e->add(['one', 'two', 'three']);
        $data = serialize($e);
        $this->assertEquals($data,
            'C:18:"vijinho\Enums\Enum":70:{a:3:{s:3:"one";s:3:"one";s:3:"two";s:3:"two";s:5:"three";s:5:"three";}}');
    }

    /**
     * @covers \vijinho\Enums\Enum::unserialize
     * @depends testSerialize
     */
    public function testUnserialize()
    {
        $e          = static::$enum;
        $serialized = serialize($e);
        $object     = unserialize($serialized);
        $this->assertEquals(get_class($object), 'vijinho\Enums\Enum');
        $this->assertEquals($object->count(), 3);
        $keys = $object->values();
        $this->assertTrue(in_array('one', $keys));
        $this->assertTrue(in_array('two', $keys));
        $this->assertTrue(in_array('three', $keys));
    }

    /**
     * @covers \vijinho\Enums\Enum::sizeof
     */
    public function testSizeof()
    {
        $e = static::$enum;
        $e->reset();
        $e->add(['one', 'two', 'three']);
        $this->assertEquals($e->sizeof(), 3);
    }

    /**
     * @covers \vijinho\Enums\Enum::toString
     */
    public function testToString()
    {
        $e = static::$enum;
        $e->reset();
        $e->add(['one', 'two', 'three']);
        $string = (string) $e;
        $this->assertEquals($string, json_encode($e->values(), JSON_PRETTY_PRINT));
    }

    /**
     * @covers \vijinho\Enums\Enum::value
     * @depends testToString
     */
    public function testValue()
    {
        $e = static::$enum;
        $this->assertEquals($e->value('one'), 'one');
    }

    /**
     * @covers \vijinho\Enums\Enum::values
     */
    public function testValues()
    {
        $e = static::$enum;
        $e->reset();
        $values = [
            'one'   => 1,
            'two'   => 2,
            'three' => 3,
        ];
        $e->add($values);
        $this->assertEquals($values, $e->values());
    }

    /**
     * @covers \vijinho\Enums\Enum::var_dump
     */
    public function testVar_dump()
    {
        $e = static::$enum;
        $e->reset();
        $values = [
            'one'   => 1,
            'two'   => 2,
            'three' => 3,
        ];
        $e->add($values);
        $this->assertEquals($e->values(), $values);
    }

    /**
     * @covers \vijinho\Enums\Enum::__call
     */
    public function test__call()
    {
        $e = static::$enum;
        $e->reset();
        $e->add('five');
        $this->assertEquals($e->five(), 'five');
    }

    /**
     * @covers \vijinho\Enums\Enum::__callStatic
     */
    public function test__callStatic()
    {
        $e = static::$enum;
        $e->add('six');
        $this->assertEquals($e->SIX(), 'six');
    }

    /**
     * Test fails on Travis, so disabled!
     *
     * @covers \vijinho\Enums\Enum::__debugInfo
     */
    public function test__debugInfo()
    {
        /*
        $e = static::$enum;
        $e->reset();
        $values = [
            'one' => 1,
            'two' => 2,
            'three' => 3
        ];
        $this->expectOutputString('array(3) {
  ["one"]=>
  int(1)
  ["two"]=>
  int(2)
  ["three"]=>
  int(3)
}
');
        var_dump($values);
*/
    }

    /**
     * @covers \vijinho\Enums\Enum::__get
     */
    public function test__get()
    {
        $e = static::$enum;
        $e->reset();
        $values = [
            'one' => 1,
        ];
        $e->add($values);
        $this->assertEquals($e->one(), 1);
    }

    /**
     * @covers \vijinho\Enums\Enum::__invoke
     */
    public function test__invoke()
    {
        $e = static::$enum;
        $e->reset();
        $values = [
            'one' => 1,
        ];
        $e($values);
        $this->assertEquals($e->value('one'), 1);
    }

    /**
     * @covers \vijinho\Enums\Enum::__isset
     */
    public function test__isset()
    {
        $e = static::$enum;
        $e->reset();
        $values = [
            'one' => 1,
        ];
        $e->add($values);
        $this->assertTrue(isset($e->one));
    }

    /**
     * @covers \vijinho\Enums\Enum::__toString
     */
    public function test__toString()
    {
        $e = static::$enum;
        $e->reset();
        $values = [
            'one' => 1,
        ];
        $e->add($values);
        $this->assertEquals((string) $e, json_encode($values, JSON_PRETTY_PRINT));
    }

    /**
     * @covers \vijinho\Enums\Enum::offsetSet
     * @covers \vijinho\Enums\Enum::offsetExists
     * @covers \vijinho\Enums\Enum::offsetUnset
     * @covers \vijinho\Enums\Enum::offsetGet
     * @link http://php.net/manual/en/class.arrayaccess.php
     */
    public function testArrayAccess()
    {
        $e = static::$enum;
        $e->reset();
        $values = [
            'apple', 'pear', 'peach',
        ];
        $e->add($values);

        $this->assertEquals('apple', $e['apple']);
        $this->assertEquals(true, isset($e['pear']));
        unset($e['pear']);
        $this->assertEquals(false, isset($e['pear']));
    }
}
