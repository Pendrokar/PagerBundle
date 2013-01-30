<?php

namespace Dmishh\Component\Pager;

use Dmishh\Component\Pager\Adapter\AdapterFactory;
use Dmishh\Component\Pager\Adapter\PagerAdapterInterface;

/**
 * Pager
 */
class Pager implements \Countable, \ArrayAccess, \Iterator
{
    /**
     * @var \Dmishh\PagerBundle\Component\Pager\Adapter\PagerAdapterInterface Adapter for extracting slice from data source
     */
    private $adapter;

    /**
     * @var int Current page
     */
    private $currentPage;

    /**
     * @var int Items per page
     */
    private $itemsPerPage;

    /**
     * @var int Total items count
     */
    private $itemsCount;

    /**
     * @var array Items on current page
     */
    private $items;

    /**
     * @var array Keys for items array. Used for preserving keys of items array
     */
    private $itemsKeys;

    /**
     * @var int Cursor for iterating over items
     */
    private $cursor = 0;

    /**
     * Constructor
     *
     * @param int $currentPage
     * @param int $itemsPerPage
     * @param mixed $dataSource Data source for pagination
     * @param array $options Options for adapter
     */
    public function __construct($currentPage = 1, $itemsPerPage = 10, $dataSource = null, array $options = array())
    {
        if ($dataSource) {
            $this->adapter = AdapterFactory::getAdapterFrom($dataSource);
        }

        $this->setCurrentPage($currentPage);
        $this->setItemsPerPage($itemsPerPage);
    }

    public function setCurrentPage($page)
    {
        $this->currentPage = $page < 1 ? 1 : $page;
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
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
     * 1 - is always valid page
     *
     * @return bool
     */
    public function isPageOutOfRange()
    {
        return $this->currentPage != 1 && ($this->currentPage < 1 || $this->currentPage > $this->getPageCount());
    }

    public function isFirstPage($page)
    {
        return $page == 1;
    }

    public function isLastPage($page)
    {
        return $page == $this->getPageCount();
    }

    public function setItemsCount($itemsCount)
    {
        $this->itemsCount = $itemsCount;
    }

    public function getItemsCount()
    {
        if (!isset($this->itemsCount)) {
            if ($this->adapter) {
                $this->itemsCount = $this->adapter->getNbResults();
            } elseif (isset($this->items)) {
                return count($this->items);
            } else {
                return 0;
            }
        }

        return $this->itemsCount;
    }

    public function setItemsPerPage($itemsPerPage)
    {
        $this->itemsPerPage = $itemsPerPage < 1 ? 1 : $itemsPerPage;
    }

    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }

    public function getOffset()
    {
        return $this->getLimit() * ($this->currentPage - 1);
    }

    /**
     * Alias for getItemsPerPage()
     *
     * @return int Limit
     */
    public function getLimit()
    {
        return $this->getItemsPerPage();
    }

    /**
     * @param array $items
     */
    public function setItems(array $items)
    {
        $this->items = array_values($items);
        $this->itemsKeys = array_keys($items);
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

    private function loadItems()
    {
        if (!isset($this->items)) {
            if ($this->adapter) {
                $this->setItems($this->adapter->getResults($this->getOffset(), $this->getLimit()));
            }
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