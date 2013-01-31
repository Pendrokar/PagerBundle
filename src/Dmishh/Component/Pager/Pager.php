<?php

/**
 * This file is part of the DmishhPagerBundle package.
 *
 * (c) 2013 Dmitriy Scherbina
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmishh\Component\Pager;

use Dmishh\Component\Pager\Adapter\AdapterFactory;
use Dmishh\Component\Pager\Adapter\PagerAdapterInterface;

/**
 * Pager
 */
class Pager implements \Countable, \ArrayAccess, \Iterator
{
    /**
     * @var \Dmishh\Component\Pager\Adapter\AdapterInterface Adapter for extracting slice from data source
     */
    protected $adapter;

    /**
     * @var int Current page
     */
    protected $page;

    /**
     * @var int Items per page
     */
    protected $itemsPerPage;

    /**
     * @var int Total items count
     */
    protected $itemsCount;

    /**
     * @var array Items on current page
     */
    protected $items;

    /**
     * @var array Keys for items array. Used for preserving keys of items array
     */
    protected $itemsKeys;

    /**
     * @var int Cursor for iterating over items
     */
    protected $cursor = 0;

    /**
     * @var bool
     */
    protected $reloadItems = true;

    /**
     * Constructor
     *
     * @param mixed $dataSource Data source for pagination
     * @param int $page
     * @param int $itemsPerPage
     * @param array $adapterOptions Options for adapter
     */
    public function __construct($dataSource, $page = 1, $itemsPerPage = 10, array $adapterOptions = array())
    {
        $this->adapter = AdapterFactory::getAdapterFrom($dataSource, $adapterOptions);
        $this->setPage($page);
        $this->setItemsPerPage($itemsPerPage);
    }

    public function setPage($page)
    {
        $this->page = $page < 1 ? 1 : $page;
        $this->reloadItems = true;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function getPageCount()
    {
        if ($this->getItemsCount() > 0) {
            return floor(($this->getItemsCount() - 1) / $this->getItemsPerPage()) + 1;
        }

        return 0;
    }

    public function hasToPaginate()
    {
        return $this->getPageCount() > 1;
    }

    /**
     * Checks that current page is out of page range
     *
     * @return bool true if page is greater than the biggest page, false — otherwise
     */
    public function isPageOutOfRange()
    {
        return $this->page != 1 && ($this->page < 1 || $this->page > $this->getPageCount());
    }

    /**
     * @param $page
     * @return bool
     */
    public function isFirstPage()
    {
        return $this->page == 1;
    }

    public function isLastPage()
    {
        return $this->page == $this->getPageCount();
    }

    public function getItemsCount()
    {
        if (!isset($this->itemsCount)) {
            $this->itemsCount = $this->adapter->getNbResults();
        }

        return $this->itemsCount;
    }

    public function setItemsPerPage($itemsPerPage)
    {
        $this->itemsPerPage = $itemsPerPage < 1 ? 1 : $itemsPerPage;
        $this->reloadItems = true;
    }

    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }

    /**
     * To be used in SQL queries
     *
     * @return int Offset for SQL query
     */
    public function getOffset()
    {
        return $this->getLimit() * ($this->page - 1);
    }

    /**
     * To be used in SQL queries
     *
     * @return int Limit for SQL query
     */
    public function getLimit()
    {
        return $this->getItemsPerPage();
    }

    /**
     * Returns results list for the current page and limit
     *
     * @return array
     */
    public function getItems()
    {
        $this->loadItems();

        return isset($this->items) ? $this->items : array();
    }


    public function reloadItems()
    {
        $this->itemsCount = $this->adapter->getNbResults();

        $items = $this->adapter->getResults($this->getOffset(), $this->getLimit());
        $this->items = array_values($items);
        $this->itemsKeys = array_keys($items);
    }

    protected function loadItems()
    {
        if (!isset($this->items) || $this->reloadItems) {
            $this->reloadItems();
            $this->reloadItems = false;
        }
    }

    /**
     * Total elements count
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    public function count()
    {
        return $this->getItemsCount();
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    public function current()
    {
        $this->loadItems();

        return $this->items[$this->itemsKeys[$this->cursor]];
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    public function next()
    {
        $this->cursor++;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed
     */
    public function key()
    {
        $this->loadItems();

        return $this->itemsKeys[$this->cursor];
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    public function valid()
    {
        $this->loadItems();

        return isset($this->items[$this->itemsKeys[$this->cursor]]);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    public function rewind()
    {
        $this->cursor = 0;
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        $this->loadItems();

        return in_array($offset, $this->itemsKeys);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        $this->loadItems();

        return $this->items[array_search($offset, $this->itemsKeys)];
    }

    /**
     * Pager is readonly - disabling items modifying
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
    }

    /**
     * Pager is readonly - disabling items modifying
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
    }
}