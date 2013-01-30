<?php
/**
 * User: dmishh
 * Date: 24.01.2013
 * Time: 13:31
 */
namespace Dmishh\PagerBundle\Repository;

use \Dmishh\PagerBundle\Component\Pager\Pager;

trait Paginatable
{
    /**
     * @param array $orderBy
     * @param int $page
     * @param int $itemsPerPage
     * @return \Dmishh\PagerBundle\Component\Pager\Pager
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
     * @return \Dmishh\PagerBundle\Component\Pager\Pager
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