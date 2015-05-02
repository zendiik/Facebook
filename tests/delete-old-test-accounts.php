<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */


require_once __DIR__ . '/../vendor/autoload.php';


$config = new Nette\Configurator();
$config->setTempDirectory(__DIR__ . '/tmp');
Kdyby\Facebook\DI\FacebookExtension::register($config);
$config->addConfig(__DIR__ . '/KdybyTests/Facebook/files/config.kdyby.neon', $config::NONE);

/** @var \Nette\DI\Container $container */
$container = $config->createContainer();

/** @var Kdyby\Facebook\Facebook $fb */
$fb = $container->getByType(Kdyby\Facebook\Facebook::class);

$iterator = $fb->iterate(sprintf('/%s/accounts/test-users', $fb->config->appId));
foreach ($iterator as $user) {
	echo "Deleting test user ", $user->id, " ... ";
	$result = $fb->api('/' . $user->id, 'DELETE');
	echo json_encode((array) $result), "\n";
}
