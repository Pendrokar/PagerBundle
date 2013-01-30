<?php

namespace Dmishh\PagerBundle\Tests\Component\Pager\Adapter;

use Dmishh\PagerBundle\Component\Pager\Adapter\ArrayAdapter;
use Dmishh\PagerBundle\Tests\Component\Pager\Util;

class ArrayAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSliceAndCount()
    {
        $itemsCount = 50;
        $items = Util::generateItems($itemsCount);

        $adapter = new ArrayAdapter($items);
        $this->assertEquals(count($items), $adapter->getNbResults());
        $this->assertEquals(array_slice($items, 5, 10), $adapter->getResults(5, 10));
    }
}
