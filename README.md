# PHP7 Enums

[![Travis CI](https://travis-ci.org/vijinho/php7-enums.svg?branch=dev-master)](https://travis-ci.org/vijinho/php7-enums)
[![Build Status](https://scrutinizer-ci.com/g/vijinho/php7-enums/badges/build.png?b=dev-master)](https://scrutinizer-ci.com/g/vijinho/php7-enums/build-status/dev-master)
[![Code Coverage](https://scrutinizer-ci.com/g/vijinho/php7-enums/badges/coverage.png?b=dev-master)](https://scrutinizer-ci.com/g/vijinho/php7-enums/?branch=dev-master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/vijinho/php7-enums/badges/quality-score.png?b=dev-master)](https://scrutinizer-ci.com/g/vijinho/php7-enums/?branch=dev-master)

This is an implementation for ENUM types in PHP7 which lives on github at [vijinho/php7-enums](https://github.com/vijinho/php7-enums). It's a little different from the other implementations I saw which didn't quite fit my needs and it uses quite a bit of PHP7 [magic](http://php.net/manual/en/language.oop5.magic.php) and [overloading](http://php.net/manual/en/language.oop5.overloading.php) to achieve the results I wanted.

## Quick Start

### Real-world example

```php
class Storage extends Enum
{
    protected static $caseSensitive = true;
    protected static $capitalize = true;

    protected static $values = [
        'BIT' => 1,
        'BYTE' => 8,
        'KILOBYTE' => 8 * 1024,
        'GIGABYTE' => 8 * 1024 * 1024 * 1024,
        'TERABYTE' => 8 * 1024 * 1024 * 1024 * 1024,
    ];
}

// add definition of a megabyte in bits
$s = new Storage;
$s(['MEGABYTE' => 1024 * Storage::KILOBYTE()]);
echo $s;

// get definition of 8 bits
$name = $s->key(8);
echo $name; // BYTE

echo $s->GIGABYTE; // 8589934592
echo $s::KILOBYTE(); // 8192
echo $s->value('TERABYTE'); // 8796093022208
echo Storage::value('BYTE'); // 8
echo Storage::BYTE(); // 8
```

### Using Enum without extending it

I do not advise you to do this because we're using static class members.

```php
use vijinho\Enums\Enum;

$e = new Enum(); // new empty enum
$e([
    'mercedes' => 'luxury',
    'ferrari' => 'sports',
    'BMW'
]);
echo $e; // outputs to JSON serialized string by magic!

/*
{
    "mercedes": "luxury",
    "ferrari": "sports",
    "BMW": "BMW"
}
*/

$e->add(['BMW' => 'Bob Marley & The Wailers']); // cannot override existing value
echo $e->value('BMW'); // BMW
$e->capitalize(true);
$e->add('Audi');
echo $e;

/*
{
    "MERCEDES": "luxury",
    "FERRARI": "sports",
    "BMW": "BMW",
    "AUDI": "Audi"
}
*/

echo $e->MERCEDES; // luxury
echo $e->FERRARI(); // sports
echo Enum::AUDI(); // Audi

// add non-string value (array)
echo "Example 17\n";
$e(['trabant' => ['Germany', 'Eastern Europe']]);

// get key by non-string
echo $e->key(['Germany', 'Eastern Europe']); // trabant

```

This is how it ought to be used:

```php
use vijinho\Enums\Enum;

class Fruits extends Enum
{
    protected static $values = [
        'apple' => 'Apple',
        'pear' => 'Pear',
        'banana' => 'Banana',
        'orange' => 'Orange',
        'grapefruit' => 'Grapefruit',
        'tomato' => 'Cucumber',
    ];
}
```

### Using ENUM statically

```php
// get an enum value by key
echo Fruits::apple(); // Apple
echo Fruits::APPLE(); // Apple

// add a key => value to the enum
Fruits::add([
	'STRAWBERRY' => 'Strawberry',
    'Avocado' => 'Avocado'
]);
// alternative way to fetch a value by key
echo Fruits::value('strawberry'); // Strawberry

// return the key for a value
echo Fruits::key('cucumber'); // tomato

// return all fruits
print_r(Fruits::values());
/*
(
    [apple] => Apple
    [pear] => Pear
    [banana] => Banana
    [orange] => Orange
    [grapefruit] => Grapefruit
    [tomato] => Cucumber
    [STRAWBERRY] => Strawberry
    [Avocado] => Avocado
)
*/
```

### Using ENUM as an object

Continuing from above...

```php
$f = new Fruits;
$f(['mango']); // add a new fruit - magic!
$f(['pineapple' => 'Pineapple']); // add another new fruit
$f->add(['potato' => 'Not a fruit']);
var_dump($f); // special var_dump magic!

object(Fruits)#5 (5) {
  ["overwrite"]=>
  bool(false)
  ["delete"]=>
  bool(false)
  ["capitalize"]=>
  bool(false)
  ["caseSensitive"]=>
  bool(false)
  ["values"]=>
  array(11) {
    ["apple"]=>
    string(5) "Apple"
    ["pear"]=>
    string(4) "Pear"
    ["banana"]=>
    string(6) "Banana"
    ["orange"]=>
    string(6) "Orange"
    ["grapefruit"]=>
    string(10) "Grapefruit"
    ["tomato"]=>
    string(8) "Cucumber"
    ["STRAWBERRY"]=>
    string(10) "Strawberry"
    ["Avocado"]=>
    string(7) "Avocado"
    ["mango"]=>
    string(5) "mango"
    ["pineapple"]=>
    string(9) "Pineapple"
    ["potato"]=>
    string(11) "Not a fruit"
  }
}
```

### Using ENUM as an array directly

Implements [PHP ArrayAccess interface](http://php.net/manual/en/class.arrayaccess.php)

```
// create a new enum $e
echo "Example 1\n";
$e = new Enum(['apple', 'pear', 'peach']);

// retrieve apple using array access
echo $e['apple']; // apple

// retrieve apple using array access
echo "Example 2\n";
echo isset($e['pear']); // 1

// remove a value
unset($e['pear']);
echo $e;

/*
{
    "apple": "apple",
    "peach": "peach"
}
*/

```

## More Usage Examples

The class uses static members, so though it can be instantiated with `new` there are some caveats detailed in the [examples](examples) folder:

- Using enums as an [object](examples/object.php)
- Using [static](examples/static.php) enums.
- Strict usage example of [strict](examples/strict.php) enums (case-sensitive, capitalized key)

## Installation

Add to your `composer.json` the following:

```json
"vijinho/enums": "dev-dev-master"
```
Then `composer update` to get it.

then import to the top of your PHP script with:

```php
use \vijinho\Enums\Enum;
```

Vijay Mahrra
http://www.urunu.com
----
