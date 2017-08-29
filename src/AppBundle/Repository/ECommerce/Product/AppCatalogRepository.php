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

use AppBundle\Entity\ECommerce\Product\AppCatalog;
use Darvin\ECommerceBundle\Repository\Product\CatalogRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Product catalog entity repository
 */
class AppCatalogRepository extends CatalogRepository
{
    /**
     * @param string $locale Locale
     *
     * @return AppCatalog[]
     */
    public function getFurnitureCatalogs($locale)
    {
        $qb = $this->createDefaultQueryBuilder()
            ->addSelect('translations')
            ->innerJoin('o.translations', 'translations');
        $this
            ->addTranslationsLocaleFilter($qb, $locale)
            ->addEnabledFilter($qb, 'translations')
            ->addNotHiddenFilter($qb, 'translations');

        $qb->andWhere('o.furniture = true');

        return $qb->getQuery()->getResult();
    }

    /**
     * @return AppCatalog[]
     */
    public function getCatalogsForHomePage()
    {
        $qb = $this->createDefaultQueryBuilder()
            ->addSelect('translations')
            ->innerJoin('o.translations', 'translations');
        $this
            ->addEnabledFilter($qb, 'translations')
            ->addNotHiddenFilter($qb, 'translations');

        $qb->andWhere('o.showOnHomePage = true');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int[] $productIds Product IDs
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getForProductCompareBuilder(array $productIds)
    {
        $qb = $this->createDefaultQueryBuilder()
            ->select('o catalog')
            ->addSelect('COUNT(products.id) product_count')
            ->addSelect('translations')
            ->innerJoin('o.products', 'products')
            ->innerJoin('o.translations', 'translations')
            ->orderBy('translations.title')
            ->groupBy('o.id');
        $this
            ->addProductIdsFilter($qb, $productIds)
            ->addEnabledFilter($qb, 'translations')
            ->addNotHiddenFilter($qb, 'translations');

        return $qb;
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $qb         Query builder
     * @param int[]                      $productIds Product IDs
     *
     * @return AppCatalogRepository
     */
    private function addProductIdsFilter(QueryBuilder $qb, array $productIds)
    {
        $qb->andWhere($qb->expr()->in('products.id', $productIds));

        return $this;
    }
}
