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

use Dmishh\Component\Pager\Exception\Exception;

class AdapterFactory
{
    /**
     * @param mixed $dataSource
     *      Supported data sources:
     *          - array
     *          - Doctrine's ArrayCollection
     *          - Doctrine's ORM QueryBuilder
     * @param array $options
     * @throws \Dmishh\Component\Pager\Exception\Exception
     * @return \Dmishh\PagerBundle\Component\Pager\Adapter\AdapterInterface
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