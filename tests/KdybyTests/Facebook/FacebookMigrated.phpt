<?php

/**
 * Test: Kdyby\Facebook\Facebook.
 *
 * @testCase KdybyTests\Facebook\FacebookTest
 * @author Filip Procházka <filip@prochazka.su>
 * @package Kdyby\Facebook
 */

namespace KdybyTests\Facebook;

use Kdyby;
use KdybyTests;
use Nette;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class FacebookMigratedTest extends KdybyTests\FacebookTestCase
{

	protected function setUp()
	{
		$this->createContainer('config.migrated.neon');
	}



	public function testGraphAPIOAuthSpecError()
	{
		$facebook = $this->createWithRequest();

		try {
			$response = $facebook->api('/me', array('client_id' => $this->config->appId));
			Assert::fail('Should not get here.');

		} catch (Kdyby\Facebook\FacebookApiException $e) {
			// means the server got the access token
			Assert::match('OAuthException: 2500: An active access token must be used to query information about the current user.', (string) $e);
		}
	}



	public function testGraphAPIMethodOAuthSpecError()
	{
		$facebook = $this->createWithRequest();

		try {
			$response = $facebook->api('/daaku.shah', 'DELETE', array('client_id' => $this->config->appId));
			Assert::fail('Should not get here.');

		} catch (Kdyby\Facebook\FacebookApiException $e) {
			Assert::match('OAuthException: 100: (#100) Can only call this method on valid test users for your app', (string) $e);
		}
	}

}

KdybyTests\run(new FacebookMigratedTest());
