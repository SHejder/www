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

use Doctrine\ORM\EntityRepository;

/**
 * Brand entity repository
 */
class BrandRepository extends EntityRepository
{
    /**
     * @param string $catalogTreePath Product catalog tree path
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getListForProductBuilder($catalogTreePath = null)
    {
        $qb = $this->createDefaultQueryBuilder();
        $qb->andWhere('o.enabled = :enabled')->setParameter('enabled', true);

        if (!empty($catalogTreePath)) {
            $qb
                ->innerJoin('o.products', 'products')
                ->innerJoin('products.catalog', 'product_catalog')
                ->andWhere('product_catalog.treePath = :product_catalog_tree_path')
                ->setParameter('product_catalog_tree_path', $catalogTreePath);
        }

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
