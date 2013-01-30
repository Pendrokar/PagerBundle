<?php

namespace Journalist\PagerBundle\Component\Pager\Adapter;

use Journalist\PagerBundle\Component\Pager\Adapter\AdapterInterface;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;

class DoctrineOrmQueryBuilderAdapter implements AdapterInterface
{
    /**
     * @var \Doctrine\ORM\QueryBuilder QueryBuilder object
     */
    private $queryBuilder;

    /**
     * @var int Hydration mode for QueryBuilder query
     */
    private $hydrationMode;

    /**
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder
     * @param array $options
     */
    public function __construct($queryBuilder, array $options = array())
    {
        $this->queryBuilder = $queryBuilder;
        $this->hydrationMode = isset($options['hydration_mode']) ? $options['hydration_mode'] : null;
    }

    /**
     * Returns the total number of results
     *
     * @return integer
     */
    public function getNbResults()
    {
        $qb = clone $this->queryBuilder;

        return $qb
            ->select('COUNT(' . $this->queryBuilder->getRootAliases()[0] . ')')
            ->resetDQLPart('orderBy')
            ->setMaxResults(null)
            ->setFirstResult(null)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Returns the list of results
     *
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getResults($offset, $limit)
    {
        return $this
            ->queryBuilder
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult($this->hydrationMode);
    }
}
