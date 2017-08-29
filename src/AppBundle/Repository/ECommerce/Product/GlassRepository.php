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
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Glass entity repository
 */
class GlassRepository extends EntityRepository
{
    /**
     * @param AppProduct $product Product
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getListForProductBuilder(AppProduct $product)
    {
        $qb = $this->createDefaultQueryBuilder();
        $qb->leftJoin('o.image', 'image');
        $qb->join('o.variants', 'variants');
        $qb->where('variants.product = :product')->setParameter('product', $product);
        $qb->andWhere('variants.enabled = :variant_enabled')->setParameter('variant_enabled', true);
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
        return $this->createQueryBuilder('o')->orderBy('o.position');
    }
}
