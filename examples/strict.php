#!/usr/bin/php -q
<?php
use vijinho\Enums\Enum;

require_once "../lib/autoload.php";

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
echo Storage::sizeof(); // 6
