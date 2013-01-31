<?php

/**
 * This file is part of the DmishhPagerBundle package.
 *
 * (c) 2013 Dmitriy Scherbina
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmishh\Component\Pager\Adapter;

use Doctrine\Common\Collections\Collection;

class DoctrineCollectionAdapter implements AdapterInterface
{
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $collection;

    /**
     * Constructor for adapter
     *
     * @param ArrayCollection $dataSource
     * @param array $options
     */
    function __construct($dataSource, array $options = array())
    {
        $this->collection = $dataSource;
    }

    /**
     * Returns the list of results
     *
     * @param int $offset
     * @param int $limit
     * @return array
     */
    function getResults($offset, $limit)
    {
        return $this->collection->slice($offset, $limit);
    }

    /**
     * Returns the total number of results
     *
     * @return integer
     */
    function getNbResults()
    {
        return $this->collection->count();
    }
}