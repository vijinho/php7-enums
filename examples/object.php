#!/usr/bin/php -q
<?php
use vijinho\Enums\Enum;

require_once '../lib/autoload.php';

// create a new empty enum $e
echo "Example 1\n";
$e = new Enum();
// print all keys, values
print_r($e->values());

// add some values
// uses the magic method __invoke()
echo "Example 2\n";
$e(['apple', 'orange', 'pear']);
// to pretty JSON-encoded string
// uses __toString() magic
echo $e;

// add a value to an existing enum using add() method
echo "Example 3\n";
$e->add(['strawberry']);
// var_dump uses __debugInfo magic
var_dump($e);

// create a new enum $new with some values
echo "Example 4\n";
$new = new Enum(['banana', 'tangerine', 'peach']);
echo $new;
echo $e;
// woah! these are identical because we use static values!

// use capitalization for keys
echo "Example 5\n";
$e->capitalize(true);
echo $e;

// get a key by name
echo "Example 6\n";
echo $e->value('banana');

// get a key by name, enable case-sensitivity, now will throw an error
echo "Example 7\n";
try {
    //enable all fetches case-insensitive
    $e->caseSensitive(true);
    echo $e->value('banana');
    // or just once (why?!)
    //echo $e->value('banana', true);
} catch (\InvalidArgumentException $ex) {
    print_r($ex);
}

// retrieve an enum magically
// uses __get()
echo "Example 8\n";
$e->caseSensitive(false);
echo($e->peach);

// retrieve an enum magically
// uses __call()
echo "Example 9\n";
echo($e->tangerine());

// fetch an enum value statically
// uses __callStatic()
echo "Example 10\n";
// call statically on object
echo($e::banana());

// check a key exists
// uses __isset()
echo "Example 11\n";
echo isset($e->apple) == false ? 'No Apple!' : 'Yes Apple!';
echo isset($e->nectarine) == false ? 'No Nectarine!' : 'Yes Nectarine!';

// serialization to/from array
// uses serialize/unserialize (from class implements Serializable)
echo "Example 12\n";
$data = $e->serialize();
echo($data);
$f = unserialize($data);
print_r($f);

// so far we have used items with matching key, value,
// but we can have different enum key values
echo "Example 13\n";
$e->reset();
$e->add([
    'Porsche' => 'fast',
    'Skoda'   => 'slow',
]);
$e(['Bugatti' => 'Ludicrous Speed!']);

// get the speed value for a Skoda
echo "Example 14\n";
echo $e->value('skoda');

// get car key for a speed value
echo "Example 15\n";
echo $e->key('fast');

// get keys only for enum
echo "Example 16\n";
print_r($e->keys());

// add non-string value
echo "Example 17\n";
$e(['trabant' => ['Germany', 'Eastern Europe']]);
echo $e;

// get key by non-string
echo "Example 18\n";
echo $e->key(['Germany', 'Eastern Europe']); // trabant
