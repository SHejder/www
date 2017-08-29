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

use AppBundle\Entity\ECommerce\Product\Variant;
use Darvin\ECommerceBundle\Entity\Product\Product;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Variant entity repository
 */
class VariantRepository extends EntityRepository
{
    /**
     * @param Product $product Product
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getListForProductBuilder(Product $product)
    {
        $qb = $this->createDefaultQueryBuilder();
        $qb->leftJoin('o.image', 'image');
        $qb->leftJoin('o.color', 'color');
        $qb->leftJoin('o.glass', 'glass');
        $qb->where('o.product = :product')->setParameter('product', $product);
        $qb->andWhere('o.enabled = :enabled')->setParameter('enabled', true);

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
        return $this->createQueryBuilder('o');
    }
}