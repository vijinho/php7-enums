<?php
/**
 * Enums - php 7 enums
 *
 * @author Vijay Mahrra <vijay@yoyo.org>
 *
 * Bootstrap for PHPUnit tests.
 */

error_reporting(E_ALL | E_STRICT);

// backward compatibility
// There is a difference between namespace structure between PHPUnit <6 and PHPUnit 6.
if (!class_exists('\PHPUnit_Framework_TestCase') && class_exists('\PHPUnit\Framework\TestCase'))
    class_alias('\PHPUnit\Framework\TestCase', '\PHPUnit_Framework_TestCase');
    
// phpunit installed with Composer
// http://stackoverflow.com/a/12798022
$loader = require __DIR__ . '/../lib/autoload.php';
$loader->add('Enums\\', __DIR__);
