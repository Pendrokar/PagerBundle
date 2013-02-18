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
        $aliases = $this->queryBuilder->getRootAliases();

        return $qb
            ->select('COUNT(' . $aliases[0] . ')')
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
