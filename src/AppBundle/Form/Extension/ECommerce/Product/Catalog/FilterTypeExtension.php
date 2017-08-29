<?php
/**
 * @author    Alexander Volodin <mr-stanlik@yandex.ru>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Extension\ECommerce\Product\Catalog;

use AppBundle\Entity\ECommerce\Product\AppProduct;
use AppBundle\Entity\ECommerce\Product\Brand;
use AppBundle\Repository\ECommerce\Product\BrandRepository;
use Darvin\ECommerceBundle\Entity\Product\PropertyValue;
use Darvin\ECommerceBundle\Form\Type\Product\Catalog\FilterType;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Product catalog filter form type
 */
class FilterTypeExtension extends AbstractTypeExtension
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @param \Doctrine\ORM\EntityManager $em Entity manager
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $prices = $this->getProductPrices($options['product_catalog_tree_path'], $options['locale']);

        $builder->add('brand', EntityType::class, array(
            'label'             => 'Производители',
            'placeholder'       => 'Выбрать из списка',
            'required'          => false,
            'class'             => Brand::class,
            'query_builder'     => function (BrandRepository $repository) use ($options) {
                return $repository->getListForProductBuilder($options['product_catalog_tree_path']);
            },
            'attr' => [
                'class' => 'brand',
            ],
        ));
        $builder->add('bestseller', CheckboxType::class, array(
            'label'    => 'Хит продаж',
            'required' => false,
        ));
        $builder->add('new', CheckboxType::class, array(
            'label'    => 'Новинки',
            'required' => false,
        ));
        $builder->add('min_price', TextType::class, array(
            'label'      => 'Цена от',
            'required'   => false,
            'empty_data' => $prices['min'],
            'data'       => $prices['min'],
            'attr'       => [
                'data-min' => $prices['min'],
            ],
        ));
        $builder->add('max_price', TextType::class, array(
            'label'      => 'Цена до',
            'required'   => false,
            'empty_data' => $prices['max'],
            'data'       => $prices['max'],
            'attr'       => [
                'data-max' => $prices['max'],
            ],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $propertyIds = [];

        foreach ($view->children as $name => $field) {
            if (!is_int($name)) {
                continue;
            }

            $propertyIds[] = $name;
        }
        foreach ($this->getBrandIdsByProductProperties($propertyIds) as $propertyId => $brandIds) {
            $field = $view->children[$propertyId];
            $field->vars['brandIds'] = $brandIds;

            /** @var \Symfony\Component\Form\ChoiceList\View\ChoiceView $choice */
            foreach ($field->vars['choices'] as $choice) {
                if (isset($brandIds[$choice->data])) {
                    $choice->attr['data-brands'] = json_encode($brandIds[$choice->data]);
                }
            }
        }
    }

    /**
     * @param string $productCatalogTreePath Product catalog tree path
     * @param string $locale                 Locale
     *
     * @return array
     */
    private function getProductPrices($productCatalogTreePath, $locale)
    {
        $array = $this->getProductRepository()->getMinAndMaxPrices(
            $locale,
            $productCatalogTreePath
        )->getQuery()->getResult();

        $array = array_shift($array);

        $result['min'] = $array[1] ? floor($array[1] !== $array[2] ? $array[1] : 0) : 0;
        $result['max'] = $array[2] ? ceil($array[2]) : 0;

        return $result;
    }

    /**
     * @param int[] $propertyIds Product property IDs
     *
     * @return array
     */
    private function getBrandIdsByProductProperties(array $propertyIds)
    {
        if (empty($propertyIds)) {
            return [];
        }

        $qb = $this->em->getRepository(PropertyValue::class)->createQueryBuilder('o');
        $rows = $qb
            ->select('o.value')
            ->addSelect('brand.id brand_id')
            ->addSelect('property.id property_id')
            ->innerJoin('o.product', 'product')
            ->innerJoin('o.property', 'property')
            ->leftJoin('product.brand', 'brand')
            ->andWhere('o.value IS NOT NULL')
            ->andWhere($qb->expr()->in('property.id', ':property_ids'))
            ->setParameter('property_ids', $propertyIds)
            ->getQuery()
            ->getScalarResult();
        $brandIds = [];

        foreach ($rows as $row) {
            $propertyId = (int) $row['property_id'];

            if (!isset($brandIds[$propertyId])) {
                $brandIds[$propertyId] = [];
            }
            foreach (explode(',', $row['value']) as $key) {
                if ('' === $key) {
                    continue;
                }

                $key = (int) $key;

                if (!isset($brandIds[$propertyId][$key])) {
                    $brandIds[$propertyId][$key] = [];
                }

                $brandId = (int) $row['brand_id'];
                $brandIds[$propertyId][$key][$brandId] = $brandId;
            }
        }

        return array_map(function (array $brandIds) {
            return array_map('array_values', $brandIds);
        }, $brandIds);
    }

    /**
     * @return \AppBundle\Repository\ECommerce\Product\AppProductRepository
     */
    private function getProductRepository()
    {
        return $this->em->getRepository(AppProduct::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return FilterType::class;
    }
}
