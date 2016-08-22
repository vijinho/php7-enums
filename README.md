# PHP7 Enums

This is an implementation for ENUM types in PHP7 which lives on github at [vijinho/php7-enums](https://github.com/vijinho/php7-enums). It's a little different from the other implementations I saw which didn't quite fit my needs and it uses quite a bit of PHP7 [magic](http://php.net/manual/en/language.oop5.magic.php) and [overloading](http://php.net/manual/en/language.oop5.overloading.php) to achieve the results I wanted.

## Quick Start

Without extending Enum, using directly (but I advise you do extend to a new class because we're using static variables for storage):

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

object(Fruits)#3 (3) {
  ["capitalise"]=>
  bool(false)
  ["case_sensitive"]=>
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

## More Usage Examples

The class uses static members, so though it can be instantiated with `new` there are some caveats detailed in the [examples](examples) folder:

- Using enums as an [object](examples/object.php)
- Using [static](examples/static.php) enums.

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
http://about.me/vijay.mahrra
----
