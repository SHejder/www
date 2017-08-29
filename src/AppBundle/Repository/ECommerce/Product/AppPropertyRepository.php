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
 * Property entity repository
 */
class AppPropertyRepository extends PropertyRepository
{
    /**
     * @param AppProduct $product Product
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getListForProductBuilder(AppProduct $product)
    {
        $qb = $this->createDefaultQueryBuilder();
//        $qb->andWhere('o.type = :type')->setParameter('type', AppProperty::TYPE_CHOICE_WITH_PRICE);
        $qb->innerJoin('o.appValues', 'app_values');
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
