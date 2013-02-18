<?php

/**
 * This file is part of the DmishhPagerBundle package.
 *
 * (c) 2013 Dmitriy Scherbina
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmishh\Component\Pager\Tests;

use Dmishh\Component\Pager\Pager;

class PagerTest extends \PHPUnit_Framework_TestCase
{

    public function testDefaults()
    {
        $pager = new Pager(array());
        $this->assertEquals(1, $pager->getPage());
        $this->assertEquals(10, $pager->getItemsPerPage());
    }

    public function testLimitAndOffset()
    {
        $pager = new Pager(array(), 1, 10);
        $this->assertEquals(0, $pager->getOffset());
        $this->assertEquals(10, $pager->getLimit());

        $pager = new Pager(array(), 2, 10);
        $this->assertEquals(10, $pager->getOffset());
        $this->assertEquals(10, $pager->getLimit());

        $pager = new Pager(array(), 3, 50);
        $this->assertEquals(100, $pager->getOffset());
        $this->assertEquals(50, $pager->getLimit());
    }

    public function testCountable()
    {
        $itemsCount = 50;
        $items = Util::generateItems($itemsCount);

        $pager = new Pager($items, 3, 10);

        $this->assertEquals($itemsCount, $pager->getItemsCount());
        $this->assertEquals($itemsCount, count($pager));
    }

    public function testArrayAccess()
    {
        $itemsCount = 15;
        $itemsPerPage = 5;
        $items = Util::generateItems($itemsCount, 'index', 'value');

        $pager = new Pager($items, 1, $itemsPerPage);

        $this->assertTrue(isset($pager['index0']));
        $this->assertEquals($items['index0'], $pager['index0']);

        // Pager has readonly access — assigning ignored
        $pager['index0'] = '123123';
        $this->assertEquals($items['index0'], $pager['index0']);

        // Pager has readonly access — unsetting ignored
        unset($pager[0]);
        $this->assertTrue(isset($pager['index0']));
        $this->assertEquals($items['index0'], $pager['index0']);
    }

    public function testIterator()
    {
        $itemsCount = 15;
        $items = Util::generateItems($itemsCount, 'index', 'value');

        $pager = new Pager($items, 2, 5);

        foreach ($pager as $key => $item) {
            $this->assertEquals($items[$key], $item);
        }
    }

    public function testPageOutOfRange()
    {
        $pager = new Pager(array(), 1, 5);
        $this->assertFalse($pager->isPageOutOfRange());
        $pager->setPage(2);
        $this->assertTrue($pager->isPageOutOfRange());

        $itemsCount = 20;
        $items = Util::generateItems($itemsCount, 'index', 'value');

        $pager = new Pager($items, 1, 5);
        $this->assertFalse($pager->isPageOutOfRange());
        $pager->setPage(2);
        $this->assertFalse($pager->isPageOutOfRange());
    }

    public function testHasToPaginate()
    {
        $items = Util::generateItems(5, 'index', 'value');
        $pager = new Pager($items, 10);
        $this->assertFalse($pager->hasToPaginate());

        $items = Util::generateItems(10, 'index', 'value');
        $pager = new Pager($items, 10);
        $this->assertFalse($pager->hasToPaginate());

        $items = Util::generateItems(11, 'index', 'value');
        $pager = new Pager($items, 10);
        $this->assertTrue($pager->hasToPaginate());
    }
}
