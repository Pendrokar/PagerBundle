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