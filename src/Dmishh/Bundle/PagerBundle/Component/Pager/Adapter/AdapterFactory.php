<?php
/**
 * User: dmishh
 * Date: 23.01.2013
 * Time: 16:44
 */
namespace Journalist\PagerBundle\Component\Pager\Adapter;

use Journalist\PagerBundle\Component\Pager\Exception\Exception;

class AdapterFactory
{
    /**
     * @param mixed $dataSource
     *      Supported data sources:
     *          - array
     *          - Doctrine's ArrayCollection
     *          - Doctrine's ORM QueryBuilder
     * @param array $options
     * @throws \Journalist\PagerBundle\Component\Pager\Exception\Exception
     * @return \Journalist\PagerBundle\Component\Pager\Adapter\AdapterInterface
     */
    public static function getAdapterFrom($dataSource, array $options = array())
    {
        if (is_object($dataSource)) {
            if ($dataSource instanceof \Doctrine\ORM\QueryBuilder) {
                return new DoctrineOrmQueryBuilderAdapter($dataSource, $options);
            } elseif ($dataSource instanceof \Doctrine\Common\Collections\Collection) {
                return new DoctrineCollectionAdapter($dataSource, $options);
            }
        } elseif (is_array($dataSource)) {
            return new ArrayAdapter($dataSource, $options);
        }

        throw new Exception('Couldn\'t find adapter for data source');
    }
}