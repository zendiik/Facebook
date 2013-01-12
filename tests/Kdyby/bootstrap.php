<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008, 2012 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

// require class loader
/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require_once __DIR__ . '/../../vendor/autoload.php';
$loader->add('Kdyby', __DIR__ . '/../../src');
unset($loader); // cleanup

define('APP_ID', '117743971608120');
define('SECRET', '9c8ea2071859659bea1246d33a9207cf');
define('MIGRATED_APP_ID', '174236045938435');
define('MIGRATED_SECRET', '0073dce2d95c4a5c2922d1827ea0cca6');
define('TEST_USER', 499834690);
define('EXPIRED_ACCESS_TOKEN', 'AAABrFmeaJjgBAIshbq5ZBqZBICsmveZCZBi6O4w9HSTkFI73VMtmkL9jLuWsZBZC9QMHvJFtSulZAqonZBRIByzGooCZC8DWr0t1M4BL9FARdQwPWPnIqCiFQ');
