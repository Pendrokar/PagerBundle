<?php

namespace Dmishh\PagerBundle\Component\Pager\Adapter;

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