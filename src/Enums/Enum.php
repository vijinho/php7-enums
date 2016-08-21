<?php

namespace vijinho\Enums;

/**
 * Abstract class for implementing ENUMs in PHP7
 *
 * @author Vijay Mahrra <vijay@yoyo.org>
 * @copyright (c) Copyright 2015 Vijay Mahrra
 * @license GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html)
 */
class Enum implements \Serializable
{
    /**
     * base enum values
     *
     * @var array $values
     */
    protected static $values = [];

    /**
     * always capitalize enum keys?
     *
     * @var boolean
     */
    protected static $capitalize = false;

    /**
     * case-sensitive check when searching by key for a value?
     *
     * @var boolean
     */
    protected static $caseSensitive = false;

    /**
     * initialize with array of additional values not existing already
     *
     * @param array static::$values
     */
    public function __construct(array $newValues = [])
    {
        $values =& static::$values;

        foreach ($values as $k => $v) {
            $oldKey = $k;
            $k = str_replace(' ', '_', $v); // space converted for magic calls
            if (!is_string($k)) {
                if (!is_string($v)) {
                    throw new \UnexpectedValueException(sprintf("Key '%s' for value '%s' is not a string!", print_r($k,1), print_r($v,1)));
                }
                // if the key is not a string, use the value if it is a string
                $k = str_replace(' ', '_', $v); // space converted for magic calls
            }
            if ($oldKey !== $k) {
                unset($values[$oldKey]);
                $values[$k] = $v;
            }
        }

        if (!empty(static::$capitalize)) {
            $values = array_change_key_case($values, CASE_UPPER);
        }

        return static::add($newValues);
    }


    /**
     * add array of extra values not existing already
     *
     * @param array|string $newValues
     * @return array static::$values
     */
    public static function add($newValues): array
    {
        // if it's a string, convert to array
        if (is_string($newValues)) {
            $newValues = [$newValues => $newValues];
        }
        $values =& static::$values;
        foreach ($newValues as $k => $v) {
            $oldKey = $k;
            $k = str_replace(' ', '_', $v); // space converted for magic calls
            if (!is_string($k)) {
                if (!is_string($v)) {
                    throw new \UnexpectedValueException(sprintf("Key '%s' for value '%s' is not a string!", print_r($k,1), print_r($v,1)));
                }
                // if the key is not a string, use the value if it is a string
                $k = str_replace(' ', '_', $v); // space converted for magic calls
            }
            $k = !empty(static::$capitalize) ? strtoupper($k) : $k;
            if (!array_key_exists($k, $values)) {
                $values[$k] = $v;
            }
        }
        return static::$values;
    }


    /**
     * get array of keys
     *
     * @return array keys of static::$values
     */
    public static function keys(): array
    {
        return array_keys(static::$values);
    }


    /**
     * get key for the given value
     *
     * @param mixed $value
     * @return string key
     */
    public static function key($value): string
    {
        $key = array_search($value, static::$values);
        if ($key === false) {
            throw new \InvalidArgumentException(sprintf("Key for value '%s' does not exist.", print_r($value,1)));
        }
        return $key;
    }


    /**
     * get existing values
     *
     * @return array static::$values
     */
    public static function values(): array
    {
        return static::$values;
    }


    /**
     * get value for the given key
     *
     * @param string $key
     * @param null|bool $caseSensitive search is case sensitive?
     * @return mixed static::$values[$key]
     */
    public static function value($key, $caseSensitive = null)
    {
        $values = static::$values;
        // if case-sensitivity is not specified use the class boolean value
        if (null === $caseSensitive) {
            $caseSensitive = static::$caseSensitive;
        }
        if (empty($caseSensitive)) {
            $key = strtoupper($key);
            $values = array_change_key_case($values, CASE_UPPER);
        }
        if (false === array_key_exists($key, $values)) {
            throw new \InvalidArgumentException(sprintf("Value for key '%s' does not exist.", print_r($key,1)));
        }
        return $values[$key];
    }


    /**
     * get value for the given key
     * method allows getting value from an object with $object->key
     *
     * @return mixed static::$values[$key]
     * @link http://php.net/manual/en/language.oop5.overloading.php#object.get
     */
    public function __get(string $key) {
        return static::value($key);
    }


    /**
     * get value named the same as the method called
     * method allows getting value from an object with $object->key()
     *
     * @param string $method
     * @param array $args
     * @return mixed static::$values[$key]
     * @link http://php.net/manual/en/language.oop5.overloading.php#object.call
     */
    public function __call(string $key, array $args = []) {
        return static::value($key);
    }


    /**
     * get value named the same as the method called statically
     * method allows getting value from an object with $object::key()
     *
     * @param string $method
     * @param array $args
     * @return mixed static::$values[$key]
     * @link http://php.net/manual/en/language.oop5.overloading.php#object.callstatic
     */
    public static function __callStatic(string $key, array $args = []) {
        return static::value($key);
    }


    /**
     * check if the given key exists via call to $object of isset($object->key)
     *
     * @param string $key
     * @return boolean
     * @link http://php.net/manual/en/language.oop5.overloading.php#object.isset
     */
    public function __isset($key)
    {
        return array_key_exists(strtoupper($key), array_change_key_case(static::$values, CASE_UPPER));
    }


    /**
     * when called as a function this class will add new values and return the result
     *
     * @param array $newValues
     * @return boolean
     * @link http://php.net/manual/en/language.oop5.overloading.php#object.invoke
     */
    public function __invoke($newValues)
    {
        return static::add($newValues);
    }


    /**
     * return the values as a string
     * method allows outputting values as a string
     *
     * @return string json_encode(static::$values)
     * @link http://php.net/manual/en/language.oop5.magic.php#object.tostring
     */
    public function __toString(): string
    {
        return json_encode(static::$values, JSON_PRETTY_PRINT);
    }

    /**
     * serialize enum values
     *
     * @return string enum values serialized
     * @link http://php.net/manual/en/class.serializable.php
     */
    public function serialize(): string
    {
        return serialize(static::$values);
    }

    /**
     * unserialize serialized values to object
     *
     * @return string enum values serialized
     * @link http://php.net/manual/en/class.serializable.php
     * @return void
     */
    public function unserialize($data) {
        static::$values = unserialize($data);
    }


    /**
     * returned values when called with var_dump()
     * only works with php 5.6+ but yet
     * for some reason this doesn't work, php bug?
     *
     * @return array debug info
     * @link http://php.net/manual/en/language.oop5.magic.php#object.debuginfo
     */
/*
    public function __debugInfo(): array
    {
        return [
            'capitalise' => static::capitalize,
            'case_sensitive' => static::case_sensitive,
            'values' => static::values
        ];
    }
*/

}
