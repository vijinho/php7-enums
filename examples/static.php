#!/usr/bin/php -q
<?php
use vijinho\Enums\Enum;

require_once "../lib/autoload.php";

class FilmRatings extends Enum
{
    protected static $capitalize = true;

    protected static $values = [
        'Blade Runner' => 10,
        'Dark Star' => 7,
        'Indiana Jones' => 8,
        'Species' => 6
    ];
}

// note that:
//  1) the spaces still exist!
//  2) the keys are not capitalised
echo "Example A\n";
var_dump(FilmRatings::values());

// SO use fix keys when calling statically
echo "Example B\n";
FilmRatings::fixKeys();
var_dump(FilmRatings::values());

// get an enum value statically, directly
// case-insensitive
echo "Example C\n";
echo FilmRatings::BLADE_Runner();
echo FilmRatings::INDIANA_JONES();

// output as JSON-encoded string
echo "Example D\n";
echo FilmRatings::toString();

// dump values (same as for var_dump on object)
echo "Example E\n";
var_dump(FilmRatings::var_dump());

// add a new value
echo "Example F\n";
var_dump(FilmRatings::add(['Star Wars' => 8]));

// you can't change a value once it's set!
echo "Example G\n";
FilmRatings::add(['Star Wars' => 10]);

// remember the values are static so NEW will return the same
echo "Example H\n";
$f = new FilmRatings();
echo $f;

// set new keys to not be capitalised
echo "Example I\n";
FilmRatings::capitalize(false);
FilmRatings::add(['Battle Beyond The Stars' => 6.5]);
echo $f; // remember this has the same data still!

// show all keys
echo "Example J\n";
print_r(FilmRatings::keys());

// get a value by key
echo "Example K\n";
print_r(FilmRatings::value('INDIANA_JONES'));

// get a key by value
echo "Example L\n";
print_r(FilmRatings::key(6));

// get a key by duplicate value
echo "Example M\n";
FilmRatings::add(['Space Hunter' => 6.5]);
// we now have 2 films rated 6.5 so an array is returned of 2 elements with each key
print_r(FilmRatings::key(6.5));

// get an invalid key throws an exception
echo "Example P\n";
try {
    FilmRatings::key(2);
} catch (\InvalidArgumentException $ex) {
    print_r($ex);
}
