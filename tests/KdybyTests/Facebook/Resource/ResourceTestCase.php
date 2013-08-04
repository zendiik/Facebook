<?php

namespace KdybyTests\Facebook\Resource;

use Kdyby\Facebook\Resource\ResourceLoader;
use Kdyby\Facebook\SignedRequest;
use KdybyTests;

/**
 * @author Martin Štekl <martin.stekl@gmail.com>
 */
abstract class ResourceTestCase extends KdybyTests\FacebookTestCase
{

	const TEST_APP_ID = "494469227315105";
	const TEST_APP_SECRET = "0a1771d3d382b4b99d9b232e662202c5";
	const TEST_APP_ACCESS_TOKEN = "494469227315105|rjCGOc1ntRu2-B2J0QaKZohrU7c";
	const TEST_USER_ID = "100006500974824";
	const TEST_USER_EMAIL = "testovaci_yqhmdhz_uzivatel@tfbnw.net";
	const TEST_USER_ACCESS_TOKEN = "CAAHBt5alf6EBAFX0NPJ7gVnRs2SAg3HPWCz8KkcH90im297dqQAXfHZBGIXA5F64NSQyib3hn3cjBfPMVw4xsbH9U5AiCEPBMaomXf48M5G3asiRGFhqtJsbWnw2A9vpdmrNVy11LiQpUbzwNVU0Y8HidZCxgZD";
	const TEST_USER_LOGIN_URL = "https://www.facebook.com/platform/test_account_login.php?user_id=100006500974824&n=kFDq84slXXRX7Gb";
	const TEST_USER_PASSWORD = "422506759";
	const TEST_USER_POST_COUNT = 5;

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
		$this->createContainer();
		$facebook = $this->createWithRequest();
		$facebook->getConfig()->appId = self::TEST_APP_ID;
		$facebook->getConfig()->appSecret = self::TEST_APP_SECRET;
		$facebook->setAccessToken(self::TEST_APP_ACCESS_TOKEN);
		$this->loader = new ResourceLoader($facebook, "/" . self::TEST_USER_ID . "/posts");
		$this->loader->setLimit(3);
	}

}
