#!/usr/bin/php -q
<?php
use vijinho\Enums\Enum;

require_once '../lib/autoload.php';

// create a new enum $e
echo "Example 1\n";
$e = new Enum(['apple', 'pear', 'peach']);

// retrieve apple using array access
echo $e['apple'];

// retrieve apple using array access
echo "Example 2\n";
echo isset($e['pear']);

// remove a value
unset($e['pear']);
echo $e;
