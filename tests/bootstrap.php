<?php
/**
 * Enums - php 7 enums
 *
 * @author Vijay Mahrra <vijay@yoyo.org>
 *
 * Bootstrap for PHPUnit tests.
 */

error_reporting(E_ALL | E_STRICT);
// Can be required more than once to allow running
// phpunit installed with Composer
// http://stackoverflow.com/a/12798022
$loader = require __DIR__ . '/../lib/autoload.php';
$loader->add('Enums\\', __DIR__);
