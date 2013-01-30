<?php

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