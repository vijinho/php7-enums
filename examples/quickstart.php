#!/usr/bin/php -q
<?php
use vijinho\Enums\Enum;

require_once "../lib/autoload.php";

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

// get an enum value by key
echo Fruits::apple(); // Apple
echo Fruits::APPLE(); // Apple

// add a key => value to the enum
Fruits::add(['STRAWBERRY' => 'Strawberry', 'Avocado' => 'Avocado']);
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

$f = new Fruits;
$f(['mango']); // add a new fruit - magic!
$f(['pineapple' => 'Pineapple']); // add another new fruit
$f->add(['potato' => 'Not a fruit']);
var_dump($f); // special var_dump magic!
