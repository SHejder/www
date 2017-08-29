<?php
/**
 * @author    Alexander Volodin <mr-stanlik@yandex.ru>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Repository\ECommerce\Product;

use AppBundle\Entity\ECommerce\Product\AppProduct;
use AppBundle\Entity\ECommerce\Product\AppProperty;
use Darvin\ECommerceBundle\Repository\Product\PropertyRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Property value entity repository
 */
class AppPropertyValueRepository extends PropertyRepository
{
    /**
     * @param AppProduct $product    Product
     * @param array      $properties Product
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getListForProductBuilder(AppProduct $product, $properties)
    {
        $qb = $this->createDefaultQueryBuilder();
        $qb->andWhere('o.property in :properties')->setParameter('properties', $properties);
        $qb->innerJoin('o.propertyPrices', 'app_values');
        $qb->innerJoin('app_values.propertyPrices', 'property_prices');
        $qb->andWhere('property_prices.product = :product')->setParameter('product', $product);

        return $qb;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getAllBuilder()
    {
        return $this->createDefaultQueryBuilder();
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function createDefaultQueryBuilder()
    {
        return $this->createQueryBuilder('o')->orderBy('o.position');
    }
}
