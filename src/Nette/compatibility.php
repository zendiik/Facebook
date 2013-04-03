<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

if (!defined('KDYBY_TO_NETTE_SERVICEINTERNAL')) {
	Nette\DI\ServiceDefinition::extensionMethod('setInject', function ($_this) {
		return $_this;
	});
	define('KDYBY_TO_NETTE_SERVICEINTERNAL', 1);
}
