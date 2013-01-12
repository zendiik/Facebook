<?php

/**
 * Test: Kdyby\Facebook\Configuration format methods.
 *
 * @author Filip Procházka <filip@prochazka.su>
 * @package Kdyby\Facebook
 */

require_once __DIR__ . '/../bootstrap.php';

$config = new \Kdyby\Facebook\Configuration('123456', '*something-really-secret*');

Tester\Assert::equal('fbsr_123456', $config->getSignedRequestCookieName());
Tester\Assert::equal('fbm_123456', $config->getMetadataCookieName());
Tester\Assert::equal('123456|*something-really-secret*', $config->getApplicationAccessToken());
Tester\Assert::equal('https://api.facebook.com/me?feed=me', (string) $config->createUrl('api', 'me', array('feed' => 'me')));
Tester\Assert::equal('https://api.facebook.com/restserver.php', (string) $config->getApiUrl('api'));
