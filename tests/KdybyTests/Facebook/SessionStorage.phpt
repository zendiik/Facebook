<?php

/**
 * Test: Kdyby\Facebook\Facebook.
 *
 * @testCase KdybyTests\Facebook\SessionStorageTest
 * @author Filip Procházka <filip@prochazka.su>
 * @package Kdyby\Facebook
 */

namespace KdybyTests\Facebook;

use Kdyby;
use Nette;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/mock.php';



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class SessionStorageTest extends Tester\TestCase
{

	/** @var \Kdyby\Facebook\Configuration */
	private $config;

	/** @var \Kdyby\Facebook\SessionStorage */
	private $session;

	/**
	 * @var \Nette\Http\SessionSection
	 */
	private $rawSection;



	/**
	 * @return \SystemContainer|\Nette\DI\Container
	 */
	protected function setUp()
	{
		$config = new Nette\Config\Configurator();
		$config->setTempDirectory(TEMP_DIR);
		Kdyby\Facebook\DI\FacebookExtension::register($config);
		$config->addConfig(__DIR__ . '/files/config.neon', $config::NONE);
		$config->addConfig(__DIR__ . '/files/nette-reset.neon', $config::NONE);

		$dic = $config->createContainer();
		/** @var \Nette\DI\Container|\SystemContainer $dic */

		$session = $dic->getByType('Nette\Http\Session');
		/** @var \Nette\Http\Session $session */
		$session->isStarted() && $session->destroy();

		$this->config = $dic->getByType('Kdyby\Facebook\Configuration');
		$this->session = $dic->getByType('Kdyby\Facebook\SessionStorage');
		$this->rawSection = $session->getSection('Facebook/' . $this->config->getApplicationAccessToken());
	}



	public function testSessionBackedFacebook()
	{
		$this->session->state = $val = 'foo';
		Assert::same($val, $this->rawSection['state']);
		Assert::same($val, $this->session->state);
	}

	public function testSessionBackedFacebookIgnoresUnsupportedKey()
	{
		$key = '--invalid--';
		$this->session->{$key} = $val = 'foo';

		Assert::false(isset($this->rawSection[$key]));
		Assert::false($this->session->{$key});
	}



	public function testClearSessionBackedFacebook()
	{
		$this->session->state = $val = 'foo';
		Assert::same($val, $this->rawSection['state']);
		Assert::same($val, $this->session->state);

		$this->session->clearAll();
		Assert::false(isset($this->rawSection['state']));
		Assert::false($this->session->state);
	}



	public function testSessionBackedFacebookIgnoresUnsupportedKeyInClear()
	{
		$this->rawSection[$key = '--invalid--'] = $val = 'foo';

		$this->session->clear($key);
		Assert::true(isset($this->rawSection[$key]));
		Assert::false($this->session->{$key});
	}



	public function testClearAllSessionBackedFacebook()
	{
		$this->session->state = $val = 'foo';
		Assert::same($val, $this->rawSection['state']);
		Assert::same($val, $this->session->state);

		$this->session->clearAll();

		Assert::false(isset($this->rawSection['state']));
		Assert::false($this->session->state);
	}

}

\run(new SessionStorageTest());
