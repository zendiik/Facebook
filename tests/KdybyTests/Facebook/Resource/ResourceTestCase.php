<?php

namespace KdybyTests\Facebook\Resource;

use Kdyby\Facebook\Resource\ResourceLoader;
use KdybyTests;
use Tester;



require_once __DIR__ . '/../../bootstrap.php';

/**
 * @author Martin Štekl <martin.stekl@gmail.com>
 */
abstract class ResourceTestCase extends KdybyTests\Facebook\FacebookTestCase
{

	/**
	 * @var \Kdyby\Facebook\Resource\ResourceLoader
	 */
	protected $loader;



	/**
	 * @return \SystemContainer|\Nette\DI\Container
	 */
	protected function setUp()
	{
		parent::setUp();
		$this->createContainer("config.kdyby.neon");
		$this->config->graphVersion = 'v2.3';
		$this->prepareTestUsers();

		$this->facebook->setAccessToken($this->testUser->access_token);

		$this->loader = new ResourceLoader($this->facebook, sprintf("/%s/feed", $this->testUser->id));
		$this->loader->setLimit(3);
	}

}
