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
use AppBundle\Entity\ECommerce\Product\AppProduct;
use Darvin\ECommerceBundle\Repository\Product\ProductRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Product
 */
class AppProductRepository extends ProductRepository
{
    /**
     * @param int $maxResults Max results
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getFeaturedBuilder($maxResults)
    {
        $qb = $this->createDefaultQueryBuilder()->setMaxResults($maxResults);
        $this
            ->addBestsellerFilter($qb)
            ->joinImages($qb)
            ->joinTranslations($qb)
            ->addEnabledFilter($qb, 'translations')
            ->addNotHiddenFilter($qb, 'translations');

        return $qb;
    }

    /**
     * @param string     $locale  Locale
     * @param AppCatalog $catalog Catalog
     *
     * @return int
     */
    public function getMinPrice($locale, $catalog)
    {
        $qb = $this->getAllBuilder($locale, $catalog);
        $qb->select('min(translations.price)')
            ->andWhere('translations.price > 0');

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param string     $locale  Locale
     * @param AppProduct $product Product
     *
     * @return AppProduct|null
     */
    public function getNextProduct($locale, $product)
    {
        $qb = $this->getAllBuilder($locale, $product->getCatalog()->getId());
        $qb->andWhere('o.position > :position')->setParameter('position', $product->getPosition());
        $qb->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param string     $locale  Locale
     * @param AppProduct $product Product
     *
     * @return AppProduct|null
     */
    public function getLastProduct($locale, $product)
    {
        $qb = $this->getAllBuilder($locale, $product->getCatalog()->getId());
        $qb->andWhere('o.position < :position')->setParameter('position', $product->getPosition())
            ->orderBy('o.position', 'desc');
        $qb->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param string  $locale    Locale
     * @param integer $catalogId Catalog Id
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getAllBuilder($locale, $catalogId)
    {
        $qb = $this->createDefaultQueryBuilder();
        $this
            ->joinImages($qb)
            ->joinTranslations($qb, $locale)
            ->addCatalogFilter($qb, $catalogId)
            ->addEnabledFilter($qb, 'translations')
            ->addNotHiddenFilter($qb, 'translations');

        return $qb;
    }

    /**
     * @param string     $locale  Locale
     * @param AppProduct $product Product
     *
     * @return AppProduct[]
     */
    public function getFurnitureProducts($locale, $product)
    {
        $qb = $this->createDefaultQueryBuilder();
//        $qb->innerJoin('o.parentsFurnitureProducts', 'parentsFurnitureProduct');
        $qb->andWhere(':product member of o.parentsFurnitureProducts')->setParameter('product', $product);
        $this
            ->joinImages($qb)
            ->joinTranslations($qb, $locale)
            ->addEnabledFilter($qb, 'translations')
            ->addNotHiddenFilter($qb, 'translations');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param string $locale    Locale
     * @param int[]  $ids       Product IDs
     * @param int    $catalogId Product catalog ID
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getForCompareBuilder($locale, array $ids, $catalogId)
    {
        $qb = $this->getByIdsEnabledBuilder($locale, $ids)
            ->addSelect('brand')
            ->addSelect('property_values')
            ->addSelect('property_values_translations')
            ->leftJoin('o.brand', 'brand')
            ->leftJoin('o.propertyValues', 'property_values')
            ->leftJoin('property_values.translations', 'property_values_translations');
        $this
            ->joinImages($qb)
            ->addCatalogFilter($qb, $catalogId);

        return $qb;
    }

    /**
     * @param string $locale             Locale
     * @param int[]  $ids                Product IDs
     * @param bool   $selectTranslations Whether to select translations
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getByIdsEnabledBuilder($locale, array $ids, $selectTranslations = true)
    {
        $qb = $this->createDefaultQueryBuilder();
        $this
            ->addIdsFilter($qb, $ids)
            ->joinTranslations($qb, $locale, $selectTranslations)
            ->addEnabledFilter($qb, 'translations');

        return $qb;
    }

    /**
     * @param string $locale    Locale
     * @param int    $catalogId Product catalog ID
     *
     * @return AppProduct[]
     */
    public function getProductsForHomePage($locale, $catalogId)
    {
        $qb = $this->getByCatalogBuilder($locale, $catalogId);

        $qb->andWhere('o.showOnHomePage = true');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $qb         Query builder
     * @param array                      $filterData Product catalog filter data
     */
    public function applyCatalogFilter(QueryBuilder $qb, array $filterData = null)
    {
        if (empty($filterData)) {
            return;
        }

        foreach ($filterData as $propertyId => $value) {
            if (null === $value) {
                continue;
            }

            if ('brand' === $propertyId && $value) {
                if ($value) {
                    $qb
                        ->andWhere('o.brand = :brand')
                        ->setParameter('brand', $value);
                }
                continue;
            }

            if ('min_price' === $propertyId && $value >= 0) {
                if ($value === 0) {
                    continue;
                }
                $qb->andWhere('translations.price >= :min_price')
                    ->setParameter('min_price', $value);
                continue;
            }

            if ('max_price' === $propertyId && $value >= 0) {
                $qb->andWhere('translations.price <= :max_price')
                    ->setParameter('max_price', $value);
                continue;
            }

            if ('bestseller' === $propertyId) {
                if ($value) {
                    $qb->andWhere('o.bestseller = :bestseller')
                        ->setParameter('bestseller', $value);
                }
                continue;
            }

            if ('new' === $propertyId) {
                if ($value) {
                    $qb->andWhere('o.new = :new')
                        ->setParameter('new', $value);
                }
                continue;
            }

            $array = is_array($value);

            if ($array && empty($value)) {
                continue;
            }

            $joinAlias = 'product_property_value_'.$propertyId;

            $propertyIdPlaceholder = 'product_property_id_'.$propertyId;

            $valuePlaceholder = 'product_property_value_value_'.$propertyId;

            $qb
                ->innerJoin('o.propertyValues', $joinAlias)
                ->andWhere(sprintf('%s.property = :%s', $joinAlias, $propertyIdPlaceholder))
                ->setParameter($propertyIdPlaceholder, $propertyId);

            if (!$array) {
                $qb
                    ->andWhere(sprintf('%s.value = :%s', $joinAlias, $valuePlaceholder))
                    ->setParameter($valuePlaceholder, $value);

                continue;
            }

            $expr = $qb->expr()->orX();

            foreach (array_values($value) as $key => $item) {
                $expr->add($qb->expr()->like(sprintf('%s.value', $joinAlias), sprintf(':%s_%s', $valuePlaceholder, $key)));
                $qb->setParameter($valuePlaceholder.'_'.$key, '%,'.$item.',%');
            }

            $qb->andWhere($expr);
        }
    }

    /**
     * @param string $locale          Locale
     * @param string $catalogTreePath Product catalog tree path
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getMinAndMaxPrices($locale, $catalogTreePath)
    {
        $qb = $this->createDefaultQueryBuilder()
            ->select('MIN(translations.price)')
            ->addSelect('MAX(translations.price)')
            ->innerJoin('o.catalog', 'catalog');
        $this
            ->addCatalogTreePathFilter($qb, $catalogTreePath)
            ->joinTranslations($qb, $locale, false)
            ->addEnabledFilter($qb, 'translations')
            ->addNotHiddenFilter($qb, 'translations');

        return $qb;
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $qb Query builder
     *
     * @return AppProductRepository
     */
    private function addBestsellerFilter(QueryBuilder $qb)
    {
        $qb->andHaving('o.bestseller = :bestseller')->setParameter('bestseller', true);

        return $this;
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $qb              Query builder
     * @param string                     $catalogTreePath Product catalog tree path
     *
     * @return AppProductRepository
     */
    private function addCatalogTreePathFilter(QueryBuilder $qb, $catalogTreePath)
    {
        $qb->andWhere('catalog.treePath = :catalog_tree_path')->setParameter('catalog_tree_path', $catalogTreePath);

        return $this;
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $qb  Query builder
     * @param int[]                      $ids Product IDs
     *
     * @return AppProductRepository
     */
    private function addIdsFilter(QueryBuilder $qb, array $ids)
    {
        if (empty($ids)) {
            throw new \RuntimeException('Product IDs array is empty.');
        }

        $qb->andWhere($qb->expr()->in('o.id', $ids));

        return $this;
    }
}
