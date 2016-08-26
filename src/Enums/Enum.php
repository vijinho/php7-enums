<?php

namespace vijinho\Enums;

/**
 * Abstract class for implementing ENUMs in PHP7
 *
 * @author Vijay Mahrra <vijay@yoyo.org>
 * @copyright (c) Copyright 2016 Vijay Mahrra
 * @license GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html)
 * @link http://php.net/manual/en/class.serializable.php
 * @link http://php.net/manual/en/class.arrayaccess.php
 */
class Enum implements \Serializable, \ArrayAccess
{
    /**
     * base enum values
     *
     * @var array $values
     */
    protected static $values = [];

    /**
     * allow keys to be unset/deleted?
     * cannot be over-ridden once defined in a class
     *
     * @var boolean
     */
    protected static $delete = false;

    /**
     * allow keys to be over-written when adding?
     * cannot be over-ridden once defined in a class
     *
     * @var boolean
     */
    protected static $overwrite = false;

    /**
     * always capitalize enum keys?
     * can be over-ridden once defined in a class
     * with capitalize(boolean) method
     *
     * @var boolean
     */
    protected static $capitalize = false;

    /**
     * case-sensitive check when searching by key for a value?
     * can be over-ridden once defined in a class
     * with caseSensitive(boolean) method
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
        static::fixKeys();
        static::add($newValues);
    }


    /**
     * Set case-sensitivity when searching
     *
     * @param boolean $bool
     */
    public static function caseSensitive($bool = false)
    {
        static::$caseSensitive = !empty($bool);
    }


    /**
     * Set capitalization for enum keys
     *
     * @param boolean $bool
     */
    public static function capitalize($bool = false)
    {
        static::$capitalize = !empty($bool);
        if (static::$capitalize) {
            static::$values = array_change_key_case(static::$values, CASE_UPPER);
        }
    }


    /**
     * Clear all data - use with care!
     *
     * @param boolean $caseSensitive
     * @param boolean $capitalize
     */
    public static function reset($caseSensitive = false, $capitalize = false)
    {
        static::$values = [];
        static::$caseSensitive = $caseSensitive;
        static::$capitalize = $capitalize;
    }


    /**
     * Remove an enum value - use with care!
     *
     */
    public static function delete($key)
    {
        $allowed = !empty(static::$delete);
        if (empty($allowed)) {
            throw new \LogicException('Method not allowed.');
        }
        $values = & static::$values;
        if (array_key_exists($key, $values)) {
            unset($values[$key]);
        } else {
            return false;
        }
        return true;
    }


    /**
     * Make sure that the enum keys have no SPACEs
     * Make sure the keys are strings
     * Set the case according to the settings
     *
     * @param return array static::$values
     */
    public static function fixKeys(): array
    {
        $values = static::$values;
        foreach ($values as $k => $v) {
            unset($values[$k]);
            if (!is_string($k)) {
                if (!is_string($v)) {
                    throw new \UnexpectedValueException(sprintf("Key '%s' for value '%s' is not a string!", print_r($k, 1), print_r($v, 1)));
                } else {
                    // if the key is not a string, use the value if it is a string
                    $k = $v;
                }
            }
            $k = str_replace(' ', '_', $k); // space converted for magic calls
            $values[$k] = $v;
        }

        static::$values = empty(static::$capitalize) ?
            $values : array_change_key_case($values, CASE_UPPER);

        return static::$values;
    }


    /**
     * add array of extra values not existing already
     *
     * @param array|string $newValues
     * @param null|boolean $overwrite allow over-write of values?
     * @return array static::$values
     */
    public static function add($newValues, $overwrite = null): array
    {
        if (empty(static::$overwrite) && !empty($overwrite)) {
            throw new \LogicException('Overwrite not allowed.');
        }

        $values = & static::$values;

        // if it's a string, convert to array
        if (is_string($newValues)) {
            $newValues = [$newValues => $newValues];
        }

        foreach ($newValues as $k => $v) {
            $overwrite = (null == $overwrite) ? static::$overwrite : $overwrite;
            if (!empty($overwrite)) {
                $values[$k] = $v;
            } elseif (!array_key_exists($k, $values)) {
                $values[$k] = $v;
            }
        }
        return static::fixKeys();
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
     * @param null|bool $caseSensitive search is case sensitive?
     * @return integer|string|array<integer|string>|null key(s)
     */
    public static function key($value, $caseSensitive = null)
    {
        $values = static::$values;
        if (!is_array($value) && !is_object($value)) {
            if (is_string($value)) {
                $value = strtoupper($value);
                // if case-sensitivity is not specified use the class boolean value
                if (null === $caseSensitive) {
                    $caseSensitive = static::$caseSensitive;
                }
                if (empty($caseSensitive)) {
                    $values = array_map(function($value) {
                        return strtoupper($value);
                    }, $values);
                }
            }
            $keys = array_keys($values, $value);
            $count = count($keys);
            if (0 === $count) {
                throw new \InvalidArgumentException(sprintf("Key for value '%s' does not exist.", print_r($value, 1)));
            }
            return count($keys) > 1 ? $keys : $keys[0];
        } elseif (is_array($value)) {
            $search = array_search($value, $values);
            if (false === $search) {
                throw new \InvalidArgumentException(sprintf("Key for value '%s' does not exist.", print_r($value, 1)));
            }
            return $search;
        }
    }


    /**
     * count values
     *
     * @return int number of values
     */
    public static function count(): int
    {
        return count(static::$values);
    }


    /**
     * count values
     *
     * @return int number of values
     */
    public static function sizeof(): int
    {
        return static::count();
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
            throw new \InvalidArgumentException(sprintf("Value for key '%s' does not exist.", print_r($key, 1)));
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
     * @param string $key the method called is used as the key
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
     * @param string $key the method called is used as the key
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
        return empty(static::$caseSensitive) ?
            array_key_exists(strtoupper($key), array_change_key_case(static::$values, CASE_UPPER)) : array_key_exists($key, static::$values);
    }


    /**
     * when called as a function this class will add new values and return the result
     *
     * @param array $newValues
     * @return array|null static::$values
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
     * return the values as a string
     * method allows outputting values as a string when called statically
     *
     * @return string json_encode(static::$values)
     */
    public static function toString(): string
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
        $data = unserialize($data);
        static::$values = $data;
    }


    /**
     * returned dump values as array
     *
     * @return array
     */
    public static function var_dump(): array
    {
        $delete = static::$delete;
        $overwrite = static::$overwrite;
        $capitalize = static::$capitalize;
        $caseSensitive = static::$caseSensitive;
        $values = static::$values;
        return [
            'overwrite' => $overwrite,
            'delete' => $delete,
            'capitalise' => $capitalize,
            'caseSensitive' => $caseSensitive,
            'values' => $values
        ];
    }


    /**
     * returned values when called with var_dump()
     *
     * @return array|null debug info
     * @link http://php.net/manual/en/language.oop5.magic.php#object.debuginfo
     */
    public function __debugInfo(): array
    {
        return static::var_dump();
    }

    /**
     * Implement Array offsetSet
     *
     * @param string $key the key to set
     * @param mixed $value the value to set
     * @return array|null debug info
     * @link http://php.net/manual/en/class.arrayaccess.php
     */
    public function offsetSet($key, $value) {
        if (is_null($key)) {
            static::$values[] = $value;
        } else {
            static::$values[$key] = $value;
        }
    }


    /**
     * Implement Array offsetExists
     *
     * @param string $key the key to set
     * @return boolean debug info
     * @link http://php.net/manual/en/class.arrayaccess.php
     */
    public function offsetExists($key) {
        return isset(static::$values[$key]);
    }


    /**
     * Implement Array offsetUnset
     *
     * @param string $key the key to set
     * @return array|null debug info
     * @link http://php.net/manual/en/class.arrayaccess.php
     */
    public function offsetUnset($key) {
        if (!empty(static::$overwrite)) {
            throw new \LogicException('Overwrite not allowed.');
        }
        unset(static::$values[$key]);
    }


    /**
     * Implement Array offsetGet
     *
     * @param string $key the key to set
     * @return array debug info
     * @link http://php.net/manual/en/class.arrayaccess.php
     */
    public function offsetGet($key) {
        return isset(static::$values[$key]) ? static::$values[$key] : null;
    }
}
