<?php

namespace KdybyTests\Facebook\Resource;

use Kdyby\Facebook\FacebookApiException;
use Kdyby\Facebook\Resource\ResourceLoader;
use KdybyTests;
use Nette\Http\UrlScript;
use Nette\Utils\ArrayHash;
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
	 * @var KdybyTests\Facebook\MockedFacebook
	 */
	protected $mockedFacebook;

	/**
	 * @var KdybyTests\Facebook\MockedApiClient
	 */
	protected $mockedApiClient;



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

		$this->mockedFacebook = $this->createWithRequest();
		$this->mockedApiClient = $this->mockedFacebook->mockApiClient();

		$queue = [];
		$this->mockedApiClient->onResponse[] = function ($url, $params) use (&$queue) {
			if (empty($params['after'])) {
				$queue = [
					$this->dataFirstPage(),
					$this->dataSecondPage(),
					$this->dataEmptyPage(),
				];
			}

			return array_shift($queue);
		};

		$this->loader = new ResourceLoader($this->mockedFacebook, sprintf("/%s/feed", $this->testUser->id));
		$this->loader->setLimit(3);
	}



	protected function dataFirstPage()
	{
		return json_encode([
			'data' => [
				[
					'id' => '10204200166197732_10205570423133299',
					'message' => 'Testovací status 5',
					'created_time' => '2015-05-02T15:30:56+0000',
					'likes' => [
						'data' => [],
						'paging' => ['cursors' => ['after' => 'MTAyMDU4MDc4MTE2OTE2MTc=', 'before' => 'MTAyMDU2NDY4MjczNjMyNjE=']],
					],
					'comments' => [
						'data' => [],
						'paging' => ['cursors' => ['after' => 'MTAyMDU4MDc4MTE2OTE2MTc=', 'before' => 'MTAyMDU2NDY4MjczNjMyNjE=']],
					],
				],
				[
					'id' => '10204200166197732_10205570322410781',
					'message' => 'Testovací status 4',
					'created_time' => '2015-05-02T14:57:27+0000',
					'likes' => [
						'data' => [],
						'paging' => ['cursors' => ['after' => 'MTAyMDU4MDc4MTE2OTE2MTc=', 'before' => 'MTAyMDU2NDY4MjczNjMyNjE=']],
					],
					'comments' => [
						'data' => [],
						'paging' => ['cursors' => ['after' => 'MTAyMDU4MDc4MTE2OTE2MTc=', 'before' => 'MTAyMDU2NDY4MjczNjMyNjE=']],
					],
				],
				[
					'id' => '10204200166197732_10205559739346211',
					'message' => 'Testovací status 3',
					'created_time' => '2015-05-01T14:02:17+0000',
					'likes' => [
						'data' => [],
						'paging' => ['cursors' => ['after' => 'MTAyMDU4MDc4MTE2OTE2MTc=', 'before' => 'MTAyMDU2NDY4MjczNjMyNjE=']],
					],
					'comments' => [
						'data' => [],
						'paging' => ['cursors' => ['after' => 'MTAyMDU4MDc4MTE2OTE2MTc=', 'before' => 'MTAyMDU2NDY4MjczNjMyNjE=']],
					],
				],
			],
			'paging' => [
				'cursors' => ['after' => 'MTAyMDU4MDc4MTE2OTE2MTc=', 'before' => 'MTAyMDU2NDY4MjczNjMyNjE='],
				'next' => (string) (new UrlScript(sprintf('https://graph.facebook.com/%s/%s/feed', $this->config->graphVersion, $this->testUser->id)))
					->appendQuery(['access_token' => $this->testUser->access_token, 'limit' => 3, 'after' => 'MTAyMDU4MDc4MTE2OTE2MTc=']),
			]
		]);
	}



	protected function dataSecondPage()
	{
		return json_encode([
			'data' => [
				[
					'id' => '10204200166197732_10205559585622368',
					'message' => 'Testovací status 2',
					'created_time' => '2015-05-01T13:47:42+0000',
					'likes' => [
						'data' => [],
						'paging' => ['cursors' => ['after' => 'MTAyMDU4MDc4MTE2OTE2MTc=', 'before' => 'MTAyMDU2NDY4MjczNjMyNjE=']],
					],
					'comments' => [
						'data' => [],
						'paging' => ['cursors' => ['after' => 'MTAyMDU4MDc4MTE2OTE2MTc=', 'before' => 'MTAyMDU2NDY4MjczNjMyNjE=']],
					],
				],
				[
					'id' => '10204200166197732_10205558918165682',
					'message' => 'Testovací status 1',
					'created_time' => '2015-04-30T19:37:26+0000',
					'likes' => [
						'data' => [],
						'paging' => ['cursors' => ['after' => 'MTAyMDU4MDc4MTE2OTE2MTc=', 'before' => 'MTAyMDU2NDY4MjczNjMyNjE=']],
					],
					'comments' => [
						'data' => [],
						'paging' => ['cursors' => ['after' => 'MTAyMDU4MDc4MTE2OTE2MTc=', 'before' => 'MTAyMDU2NDY4MjczNjMyNjE=']],
					],
				],
			],
			'paging' => [
				'cursors' => ['after' => 'MTAyMDU4MDc4MTE2OTE2MTc=', 'before' => 'MTAyMDU2NDY4MjczNjMyNjE='],
				'previous' => (string) (new UrlScript(sprintf('https://graph.facebook.com/%s/%s/feed', $this->config->graphVersion, $this->testUser->id)))
					->appendQuery(['access_token' => $this->testUser->access_token, 'limit' => 3, 'before' => 'MTAyMDU4MDc4MTE2OTE2MTc=']),
			]
		]);
	}



	protected function dataEmptyPage()
	{
		return json_encode([
			'data' => [],
			'paging' => [],
		]);
	}

}
