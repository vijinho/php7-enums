#!/usr/bin/php -q
<?php
use vijinho\Enums\Enum;

require_once "../lib/autoload.php";

class Storage extends Enum
{
    protected static $delete = true;
    protected static $overwrite = true;
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
echo $s;

// allow over-writing
$s->add(['BIT' => 2], true);
echo $s;

// allow deletion
$s->unset('TERABYTE');
echo $s;
