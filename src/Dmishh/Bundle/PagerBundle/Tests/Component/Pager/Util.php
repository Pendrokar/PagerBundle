<?php

namespace Journalist\PagerBundle\Tests\Component\Pager;


class Util
{
    /**
     * @param int $count
     * @return array
     */
    public static function generateItems($count = 50, $indexPrefix = '', $valuePrefix = '')
    {
        $items = array();

        for ($i = 0; $i < $count; $i++) {
            $items[$indexPrefix . $i] = $valuePrefix . $i;
        }

        return $items;
    }
}