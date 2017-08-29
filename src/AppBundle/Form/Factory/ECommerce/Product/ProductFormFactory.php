<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Factory\ECommerce\Product;

use AppBundle\ECommerce\Product\Comparable;
use AppBundle\Entity\ECommerce\Product\AppProduct;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * E-commerce product form factory
 */
class ProductFormFactory
{
    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    private $genericFormFactory;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @param \Symfony\Component\Form\FormFactoryInterface $genericFormFactory Generic form factory
     * @param \Symfony\Component\Routing\RouterInterface   $router             Router
     */
    public function __construct(FormFactoryInterface $genericFormFactory, RouterInterface $router)
    {
        $this->genericFormFactory = $genericFormFactory;
        $this->router = $router;
    }

    /**
     * @param \AppBundle\Entity\ECommerce\Product\AppProduct[] $products Products
     *
     * @return \Symfony\Component\Form\FormView[]
     */
    public function createCompareAddFormViews(array $products)
    {
        $views = [];

        foreach ($products as $product) {
            $views[$product->getId()] = $this->createCompareAddFormView($product);
        }

        return $views;
    }

    /**
     * @param \AppBundle\Entity\ECommerce\Product\AppProduct $product Product
     *
     * @return \Symfony\Component\Form\FormView
     */
    public function createCompareAddFormView(AppProduct $product)
    {
        return $this->createCompareAddForm($product)->createView();
    }

    /**
     * @param \AppBundle\Entity\ECommerce\Product\AppProduct $product    Product
     * @param \AppBundle\ECommerce\Product\Comparable        $comparable Comparable
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCompareAddForm(AppProduct $product, Comparable $comparable = null)
    {
        $name = sprintf('app_ecommerce_product_%d_compare_add', $product->getId());

        if (empty($comparable)) {
            $comparable = new Comparable($product->getId());
        }

        $builder = $this->genericFormFactory->createNamedBuilder($name, FormType::class, $comparable, [
            'data_class'    => Comparable::class,
            'csrf_token_id' => md5(__FILE__.__METHOD__.$name),
            'action'        => $this->router->generate('app_ecommerce_product_compare_add', [
                'id' => $product->getId(),
            ]),
        ]);

        return $builder
            ->add('productId', HiddenType::class)
            ->add('colorId', HiddenType::class, [
                'attr' => [
                    'class' => 'color_id',
                ],
            ])
            ->add('glassId', HiddenType::class, [
                'attr' => [
                    'class' => 'glass_id',
                ],
            ])
            ->getForm();
    }

    /**
     * @param \AppBundle\Entity\ECommerce\Product\AppProduct[] $products Products
     *
     * @return \Symfony\Component\Form\FormView[]
     */
    public function createCompareDeleteFormViews(array $products)
    {
        $views = [];

        foreach ($products as $product) {
            $views[$product->getId()] = $this->createCompareDeleteFormView($product);
        }

        return $views;
    }

    /**
     * @param \AppBundle\Entity\ECommerce\Product\AppProduct $product Product
     *
     * @return \Symfony\Component\Form\FormView
     */
    public function createCompareDeleteFormView(AppProduct $product)
    {
        return $this->createCompareDeleteForm($product)->createView();
    }

    /**
     * @param \AppBundle\Entity\ECommerce\Product\AppProduct $product Product
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCompareDeleteForm(AppProduct $product)
    {
        $name = sprintf('app_ecommerce_product_%d_compare_delete', $product->getId());

        $builder = $this->genericFormFactory->createNamedBuilder(
            $name,
            FormType::class,
            [
                'id' => $product->getId(),
            ],
            [
                'csrf_token_id' => md5(__FILE__.__METHOD__.$name),
                'action'        => $this->router->generate('app_ecommerce_product_compare_delete', [
                    'id' => $product->getId(),
                ]),
            ]
        );

        return $builder
            ->add('id', HiddenType::class)
            ->getForm();
    }
}
