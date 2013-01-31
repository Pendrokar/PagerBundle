<?php

/**
 * This file is part of the DmishhPagerBundle package.
 *
 * (c) 2013 Dmitriy Scherbina
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

        $pager = new Pager($items, 1, $itemsPerPage);
        $this->assertEquals(count($items), count($pager));
        $this->assertEquals(array_slice($items, 0, 5, true), $pager->getItems());
    }
}
