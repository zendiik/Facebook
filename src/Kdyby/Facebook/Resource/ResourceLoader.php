<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Kdyby\Facebook\Resource;

use IteratorAggregate;
use Kdyby\Facebook\Facebook;
use Nette\ArrayHash;
use Nette\Object;
use Nette\Utils\Strings;
use Traversable;



/**
 * @author Martin Štekl <martin.stekl@gmail.com>
 */
class ResourceLoader extends Object implements IteratorAggregate, IResourceLoader
{

	/**
	 * @var \Kdyby\Facebook\Facebook
	 */
	private $facebook;

	/**
	 * @var string
	 */
	private $resourcePath;

	/**
	 * @var string[]
	 */
	private $fields = array();

	/**
	 * @var int|NULL
	 */
	private $limit = NULL;

	/**
	 * @var \Nette\ArrayHash|NULL
	 */
	private $lastResult = NULL;



	/**
	 * Creates new list of Facebook objects.
	 *
	 * @param \Kdyby\Facebook\Facebook $facebook
	 * @param string $resourcePath
	 */
	public function __construct(Facebook $facebook, $resourcePath)
	{
		$this->facebook = $facebook;
		$this->resourcePath = $resourcePath;
	}



	/**
	 * Sets list of fields which will be selected.
	 *
	 * @param string[] $fields
	 * @return \Kdyby\Facebook\Resource\ResourceLoader
	 */
	public function setFields(array $fields = array())
	{
		$this->fields = $fields;

		return $this;
	}



	/**
	 * Adds field to list of fields which will be selected.
	 *
	 * @param string $field
	 * @return \Kdyby\Facebook\Resource\ResourceLoader
	 */
	public function addField($field)
	{
		if (!in_array($field, $this->fields)) {
			$this->fields[] = $field;
		}

		return $this;
	}



	/**
	 * @return string[]
	 */
	public function getFields()
	{
		return $this->fields;
	}



	/**
	 * @param int|NULL $limit
	 * @return \Kdyby\Facebook\Resource\ResourceLoader
	 */
	public function setLimit($limit = NULL)
	{
		$this->limit = is_numeric($limit) && $limit > 0 ? intval(round($limit)) : NULL;

		return $this;
	}



	/**
	 * @return int|NULL
	 */
	public function getLimit()
	{
		return $this->limit;
	}



	/**
	 * Loads first data source.
	 */
	private function load()
	{
		if ($this->lastResult === NULL) {
			$this->lastResult = $this->facebook->api($this->constructInitialPath());
		} elseif ($this->hasNextPage()) {
			$this->lastResult = $this->facebook->api($this->getNextPath());
		}
	}



	/**
	 * Constructs initial path to the resource.
	 *
	 * @return string
	 */
	private function constructInitialPath()
	{
		$query = array();
		if ($this->fields) {
			$query["fields"] = implode(",", $this->fields);
		}
		if ($this->limit !== NULL) {
			$query["limit"] = $this->limit;
		}

		return "/" . $this->resourcePath . ($query ? "?" . http_build_query($query) : "");
	}



	/**
	 * Checks if list has next page.
	 *
	 * @return bool
	 */
	private function hasNextPage()
	{
		return !empty($this->lastResult->paging->next);
	}



	/**
	 * Parses path of next resource page from current data.
	 *
	 * @return string
	 */
	private function getNextPath()
	{
		return $this->lastResult->paging->next;
	}



	/**
	 * Returns collections of data from data source at one page.
	 *
	 * @return \Nette\ArrayHash
	 */
	public function getNextPage()
	{
		$this->load();

		return $this->lastResult ? $this->lastResult->data : ArrayHash::from(array());
	}



	/**
	 * Resets loader to first data source.
	 *
	 * @return \Kdyby\Facebook\Resource\IResourceLoader
	 */
	public function reset()
	{
		$this->lastResult = NULL;

		return $this;
	}



	/**
	 * Retrieve an external iterator.
	 *
	 * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
	 * @return Traversable An instance of an object implementing <b>Iterator</b> or <b>Traversable</b>
	 */
	public function getIterator()
	{
		return new ResourceIterator($this);
	}

}
