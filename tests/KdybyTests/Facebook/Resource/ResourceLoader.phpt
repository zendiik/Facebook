<?php

/**
 * Test: Kdyby\Facebook\Resource\ResourceLoader.
 *
 * @testCase KdybyTests\Facebook\Resource\ResourceLoaderTest
 * @author Martin Štekl <martin.stekl@gmail.com>
 * @package Kdyby\Facebook\Resource
 */

namespace KdybyTests\Facebook\Resource;

use Iterator;
use Kdyby\Facebook\Resource\ResourceIterator;
use KdybyTests;
use Nette\Utils\ArrayHash;
use Tester\Assert;



require_once __DIR__ . '/../../bootstrap.php';

/**
 * @author Martin Štekl <martin.stekl@gmail.com>
 */
class ResourceLoaderTest extends ResourceTestCase
{

	public function testAddField()
	{
		$loader = $this->loader;
		Assert::same([], $loader->getFields());

		$loader->addField("message");
		Assert::same(["message"], $loader->getFields());

		$loader->addField("from");
		Assert::same(["message", "from"], $loader->getFields());
	}



	public function testSetFields()
	{
		$loader = $this->loader;
		Assert::same([], $loader->getFields());

		$loader->setFields(["message"]);
		Assert::same(["message"], $loader->getFields());

		$loader->setFields(["from"]);
		Assert::same(["from"], $loader->getFields());

		$loader->setFields();
		Assert::same([], $loader->getFields());
	}



	public function testSetLimit()
	{
		$loader = $this->loader;

		$loader->setLimit();
		Assert::equal(NULL, $loader->getLimit());

		$loader->setLimit(10);
		Assert::equal(10, $loader->getLimit());

		$loader->setLimit("10");
		Assert::equal(10, $loader->getLimit());

		$loader->setLimit(10.07);
		Assert::equal(10, $loader->getLimit());

		$loader->setLimit("10.07");
		Assert::equal(10, $loader->getLimit());

		$loader->setLimit(10.57);
		Assert::equal(11, $loader->getLimit());

		$loader->setLimit("10.57");
		Assert::equal(11, $loader->getLimit());

		$loader->setLimit(0);
		Assert::equal(NULL, $loader->getLimit());

		$loader->setLimit("0");
		Assert::equal(NULL, $loader->getLimit());

		$loader->setLimit(-10);
		Assert::equal(NULL, $loader->getLimit());

		$loader->setLimit("-10");
		Assert::equal(NULL, $loader->getLimit());

		$loader->setLimit("");
		Assert::equal(NULL, $loader->getLimit());

		$loader->setLimit("test");
		Assert::equal(NULL, $loader->getLimit());

		$loader->setLimit(new \stdClass());
		Assert::equal(NULL, $loader->getLimit());
	}



	public function testGetNextPage()
	{
		$loader = $this->loader;
		$loader->setFields(["id", "message"]);

		$nextPage = array_values((array) $loader->getNextPage());
		Assert::equal("Testovací status 5", $nextPage[0]->message);
		Assert::equal("Testovací status 4", $nextPage[1]->message);
		Assert::equal("Testovací status 3", $nextPage[2]->message);

		$nextPage = array_values((array) $loader->getNextPage());
		Assert::equal("Testovací status 2", $nextPage[0]->message);
		Assert::equal("Testovací status 1", $nextPage[1]->message);

		Assert::count(0, $loader->getNextPage());
	}



	public function testReset()
	{
		$loader = $this->loader;
		$loader->setFields(["id", "message"]);

		$nextPage = array_values((array) $loader->getNextPage());
		Assert::equal("Testovací status 5", $nextPage[0]->message);
		Assert::equal("Testovací status 4", $nextPage[1]->message);
		Assert::equal("Testovací status 3", $nextPage[2]->message);

		$nextPage = array_values((array) $loader->getNextPage());
		Assert::equal("Testovací status 2", $nextPage[0]->message);
		Assert::equal("Testovací status 1", $nextPage[1]->message);

		$loader->reset();

		$nextPage = array_values((array) $loader->getNextPage());
		Assert::count(2, $loader->getNextPage());
		Assert::equal("Testovací status 5", $nextPage[0]->message);
		Assert::equal("Testovací status 4", $nextPage[1]->message);
		Assert::equal("Testovací status 3", $nextPage[2]->message);
	}



	public function testGetIterator()
	{
		$loader = $this->loader;
		Assert::true($loader->getIterator() instanceof Iterator);
		Assert::true($loader->getIterator() instanceof ResourceIterator);
	}

}

KdybyTests\run(new ResourceLoaderTest());
