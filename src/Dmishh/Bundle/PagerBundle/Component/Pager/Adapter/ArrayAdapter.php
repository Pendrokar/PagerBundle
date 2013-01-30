<?php

namespace Journalist\PagerBundle\Component\Pager\Adapter;

class ArrayAdapter implements AdapterInterface
{
    /**
     * @var array
     */
    private $array;

    /**
     * Constructor for adapter
     *
     * @param array $dataSource
     * @param array $options
     */
    function __construct($dataSource, array $options = array())
    {
        $this->array = $dataSource;
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
        return array_slice($this->array, $offset, $limit);
    }

    /**
     * Returns the total number of results
     *
     * @return integer
     */
    function getNbResults()
    {
        return count($this->array);
    }
}