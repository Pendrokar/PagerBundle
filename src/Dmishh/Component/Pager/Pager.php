<?php

namespace Dmishh\PagerBundle\Component\Pager;

use Dmishh\PagerBundle\Component\Pager\Adapter\AdapterFactory;
use Dmishh\PagerBundle\Component\Pager\Adapter\PagerAdapterInterface;

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
     * @var int Cursor for iterating over items
     */
    private $cursor = 0;

    /**
     * Constructor
     *
     * @param int $currentPage
     * @param int $itemsPerPage
     * @param mixed $dataSource
     * @param array $options
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

    public function getLimit()
    {
        return $this->itemsPerPage;
    }

    public function setItems(array $items)
    {
        $this->items = $items;
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
                $this->items = array_values($this->adapter->getResults($this->getOffset(), $this->getLimit()));
            }
        }
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return $this->getItemsCount();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        $this->loadItems();
        return $this->items[$this->cursor];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->cursor++;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        $this->loadItems();
        $this->items[$this->cursor];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        $this->loadItems();
        return isset($this->items[$this->cursor]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->cursor = 0;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        $this->loadItems();
        return isset($this->items[$offset]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        $this->loadItems();
        return $this->items[$offset];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
    }
}