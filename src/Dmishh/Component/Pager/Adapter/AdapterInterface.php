<?php

/**
 * This file is part of the DmishhPagerBundle package.
 *
 * (c) 2013 Dmitriy Scherbina
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace  Dmishh\Component\Pager\Adapter;

interface AdapterInterface
{
    /**
     * Constructor for adapter
     *
     * @param mixed $dataSource
     * @param array $options
     */
    function __construct($dataSource, array $options = array());

    /**
     * Returns the list of results
     *
     * @param int $offset
     * @param int $limit
     * @return array
     */
    function getResults($offset, $limit);

    /**
     * Returns the total number of results
     * 
     * @return integer
     */
    function getNbResults();
}