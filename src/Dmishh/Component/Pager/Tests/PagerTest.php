<?php

namespace Dmishh\Component\Pager\Tests;

use Dmishh\Component\Pager\Pager;

class PagerTest extends \PHPUnit_Framework_TestCase
{

    public function testDefaults()
    {
        $pager = new Pager();
        $this->assertEquals(1, $pager->getCurrentPage());
        $this->assertEquals(10, $pager->getItemsPerPage());
    }

    public function testLimitAndOffset()
    {
        $pager = new Pager(1, 10);
        $this->assertEquals(0, $pager->getOffset());
        $this->assertEquals(10, $pager->getLimit());

        $pager = new Pager(2, 10);
        $this->assertEquals(10, $pager->getOffset());
        $this->assertEquals(10, $pager->getLimit());

        $pager = new Pager(3, 50);
        $this->assertEquals(100, $pager->getOffset());
        $this->assertEquals(50, $pager->getLimit());
    }

    public function testEmptyDataSource()
    {
        $pager = new Pager(5, 10);
        $this->assertEquals(array(), $pager->getItems());
        $this->assertEquals(0, $pager->getItemsCount());
    }

    public function testCustomData()
    {
        $itemsCount = 50;
        $items = Util::generateItems($itemsCount);

        $pager = new Pager(1, 50);
        $pager->setItems($items);

        $this->assertEquals($items, $pager->getItems());
    }

    public function testCountable()
    {
        $itemsCount = 50;
        $items = Util::generateItems($itemsCount);

        $pager = new Pager(3, 10);
        $pager->setItems($items);

        $this->assertEquals($itemsCount, $pager->getItemsCount());
        $this->assertEquals($itemsCount, count($pager));
    }

    public function testArrayAccess()
    {
        $itemsCount = 15;
        $itemsPerPage = 5;
        $items = Util::generateItems($itemsCount, 'index', 'value');

        $pager = new Pager(1, $itemsPerPage);
        $pager->setItems($items);

        $this->assertTrue(isset($pager['index0']));
        $this->assertEquals($items['index0'], $pager['index0']);

        $pager['index0'] = '123123';
        $this->assertEquals($items['index0'], $pager['index0']);

        unset($pager[0]);
        $this->assertTrue(isset($pager['index0']));
        $this->assertEquals($items['index0'], $pager['index0']);
    }

    public function testIterator()
    {
        $itemsCount = 15;
        $itemsPerPage = 5;
        $items = Util::generateItems($itemsCount, 'index', 'value');

        $pager = new Pager(2, $itemsPerPage);
        $pager->setItems($items);

        $pager->rewind();

        while ($pager->valid()) {
            $key = $pager->key();
            $this->assertEquals($items[$key], $pager->current());
            $pager->next();
        }
    }

    public function testPageOutOfRange()
    {
        $pager = new Pager(1, 10);
        $this->assertFalse($pager->isPageOutOfRange());

        $pager->setCurrentPage(2);
        $this->assertTrue($pager->isPageOutOfRange());

        $pager->setItems(Util::generateItems(20));
        $this->assertFalse($pager->isPageOutOfRange());
    }

    public function testHasToPaginate()
    {
        $pager = new Pager(1, 10);

        $pager->setItemsCount(5);
        $this->assertFalse($pager->hasToPaginate());

        $pager->setItemsCount(10);
        $this->assertFalse($pager->hasToPaginate());

        $pager->setItemsCount(11);
        $this->assertTrue($pager->hasToPaginate());
    }
}
