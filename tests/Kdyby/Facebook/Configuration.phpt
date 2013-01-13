<?php

/**
 * Test: Kdyby\Facebook\Configuration format methods.
 *
 * @author Filip Procházka <filip@prochazka.su>
 * @package Kdyby\Facebook
 */

namespace KdybyTests\Facebook;

use Kdyby\Facebook\Configuration;
use Tester\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class ConfigurationTest extends TestCase
{

	public function testFunctionality()
	{
		$config = new Configuration('123456', '*something-really-secret*');

		Assert::equal('fbsr_123456', $config->getSignedRequestCookieName());
		Assert::equal('fbm_123456', $config->getMetadataCookieName());
		Assert::equal('123456|*something-really-secret*', $config->getApplicationAccessToken());
		Assert::equal('https://api.facebook.com/me?feed=me', (string) $config->createUrl('api', 'me', array('feed' => 'me')));
		Assert::equal('https://api.facebook.com/restserver.php', (string) $config->getApiUrl('api'));
	}

}

\run(new ConfigurationTest());
