<?php

namespace Dmishh\Component\Pager\Tests\Adapter;

use Dmishh\Component\Pager\Pager;
use Dmishh\Component\Pager\Adapter\ArrayAdapter;
use Dmishh\Component\Pager\Tests\Util;

class ArrayAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSliceAndCount()
    {
        $itemsCount = 50;
        $items = Util::generateItems($itemsCount);

        $adapter = new ArrayAdapter($items);
        $this->assertEquals(count($items), $adapter->getNbResults());
        $this->assertEquals(array_slice($items, 5, 10, true), $adapter->getResults(5, 10));
    }

    public function testLoadItems()
    {
        $itemsCount = 15;
        $itemsPerPage = 5;
        $items = Util::generateItems($itemsCount);

        $pager = new Pager(1, $itemsPerPage, $items);
        $this->assertEquals(count($items), count($pager));
        $this->assertEquals(array_slice($items, 0, 5, true), $pager->getItems());
    }
}
