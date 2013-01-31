<?php

/**
 * This file is part of the DmishhPagerBundle package.
 *
 * (c) 2013 Dmitriy Scherbina
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmishh\Bundle\PagerBundle\Repository;

use Dmishh\Component\Pager\Pager;

trait PaginateTrait
{
    /**
     * @param array $orderBy
     * @param int $page
     * @param int $itemsPerPage
     * @return \Dmishh\Component\Pager\Pager
     */
    public function paginate($page, $itemsPerPage, array $orderBy = null)
    {
        return $this->paginateBy($page, $itemsPerPage, array(), $orderBy);
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param int $page
     * @param int $itemsPerPage
     * @return \Dmishh\Component\Pager\Pager
     */
    public function paginateBy($page, $itemsPerPage, array $criteria, array $orderBy = null)
    {
        $alias = 'e';

        $queryBuilder = $this->createQueryBuilder($alias);

        foreach ($criteria as $fieldName => $value) {
            if ($value === null) {
                $queryBuilder
                    ->andWhere($alias . '.' . $fieldName . ' IS NULL');
            } else {
                $queryBuilder
                    ->andWhere($alias . '.' . $fieldName . ' = :' . $fieldName)
                    ->setParameter(':' . $fieldName, $value);
            }

            if ($orderBy) {
                foreach ($orderBy as $fieldName => $orderDirection) {
                    $queryBuilder->orderBy($alias . '.' . $fieldName, $orderDirection);
                }
            }
        }

        return new Pager($page, $itemsPerPage, $queryBuilder);
    }
}