<?php
/**
 * @author    Alexander Volodin <mr-stanlik@yandex.ru>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig\Extension\ECommerce;

use AppBundle\ECommerce\Product\CompareManager;
use AppBundle\Entity\ECommerce\Product\AppCatalog;
use AppBundle\Entity\ECommerce\Product\AppProduct;
use AppBundle\Entity\ECommerce\Product\AppProperty;
use AppBundle\Entity\ECommerce\Product\Color;
use AppBundle\Entity\ECommerce\Product\Glass;
use AppBundle\Entity\ECommerce\Product\Interior;
use AppBundle\Entity\ECommerce\Product\Variant;
use AppBundle\Form\Factory\ECommerce\Product\ProductFormFactory;
use AppBundle\Repository\ECommerce\Product\AppCatalogRepository;
use AppBundle\Repository\ECommerce\Product\AppProductRepository;
use AppBundle\Repository\ECommerce\Product\AppPropertyRepository;
use AppBundle\Repository\ECommerce\Product\ColorRepository;
use AppBundle\Repository\ECommerce\Product\GlassRepository;
use AppBundle\Repository\ECommerce\Product\InteriorRepository;
use AppBundle\Repository\ECommerce\Product\VariantRepository;
use Darvin\ECommerceBundle\Entity\Product\Catalog;
use Darvin\ECommerceBundle\Form\Factory\Cart\CartFormFactory;
use Darvin\Utils\Locale\LocaleProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormView;

/**
 * Product extension
 */
class ProductExtension extends \Twig_Extension
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Darvin\Utils\Locale\LocaleProvider
     */
    private $localeProvider;

    /**
     * @var \AppBundle\ECommerce\Product\CompareManager
     */
    private $productCompareManager;

    /**
     * @var \AppBundle\Form\Factory\ECommerce\Product\ProductFormFactory
     */
    private $productFormFactory;

    /**
     * @var \Darvin\ECommerceBundle\Form\Factory\Cart\CartFormFactory
     */
    private $cartFormFactory;

    /**
     * @param \Doctrine\ORM\EntityManager                                  $em                    Entity Manager
     * @param \Darvin\Utils\Locale\LocaleProvider                          $localeProvider        Locale Provider
     * @param \AppBundle\ECommerce\Product\CompareManager                  $productCompareManager E-commerce product compare manager
     * @param \AppBundle\Form\Factory\ECommerce\Product\ProductFormFactory $productFormFactory    E-commerce product form factory
     * @param \Darvin\ECommerceBundle\Form\Factory\Cart\CartFormFactory    $cartFormFactory       Cart Form Factory
     */
    public function __construct(
        EntityManager $em,
        LocaleProvider $localeProvider,
        CompareManager $productCompareManager,
        ProductFormFactory $productFormFactory,
        CartFormFactory $cartFormFactory
    ) {
        $this->em = $em;
        $this->localeProvider = $localeProvider;
        $this->productCompareManager = $productCompareManager;
        $this->productFormFactory = $productFormFactory;
        $this->cartFormFactory = $cartFormFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('app_show_catalogs_on_home', [$this, 'appShowCatalogsOnHomeWidget'], [
                'is_safe'           => ['html'],
                'needs_environment' => true,
            ]),
            new \Twig_SimpleFunction('app_show_products_on_home', [$this, 'appShowProductsOnHomeWidget'], [
                'is_safe'           => ['html'],
                'needs_environment' => true,
            ]),
            new \Twig_SimpleFunction('app_show_product_in_interiors', [$this, 'appShowProductInInteriorsWidget'], [
                'is_safe'           => ['html'],
                'needs_environment' => true,
            ]),
            new \Twig_SimpleFunction('app_show_fitting', [$this, 'appShowFittingWidget'], [
                'is_safe'           => ['html'],
                'needs_environment' => true,
            ]),
            new \Twig_SimpleFunction('app_properties_list', [$this, 'renderPropertiesList']),
            new \Twig_SimpleFunction('app_show_calculator', [$this, 'renderShowCalculator'], [
                'needs_environment' => true,
                'is_safe'           => ['html'],
            ]),
            new \Twig_SimpleFunction('app_count_products_to_compare', [$this->productCompareManager, 'count']),
            new \Twig_SimpleFunction('app_product_compare_add', [$this, 'renderCompareAddWidget'], [
                'is_safe'           => ['html'],
                'needs_environment' => true,
            ]),
            new \Twig_SimpleFunction('product_last_and_next', [$this, 'renderProductLastAndNext'], [
                'needs_environment' => true,
                'is_safe'           => ['html'],
            ]),
            new \Twig_SimpleFunction('furniture_list_for_product', [$this, 'renderFurnitureListForProduct'], [
                'needs_environment' => true,
                'is_safe'           => ['html'],
            ]),
            new \Twig_SimpleFunction('furniture_forms_list_for_product', [$this, 'renderFurnitureFormsListForProduct'], [
                'needs_environment' => true,
                'is_safe'           => ['html'],
            ]),
            new \Twig_SimpleFunction('app_cart_add_form', [$this, 'cartAddForm']),
            new \Twig_SimpleFunction('app_get_min_price', [$this, 'getMinPriceForCatalog'], [
                'needs_environment' => true,
                'is_safe'           => ['html'],
            ]),
        ];
    }

    /**
     * @param \Twig_Environment                              $env     Environment
     * @param \AppBundle\Entity\ECommerce\Product\AppProduct $product Product
     *
     * @return string
     */
    public function renderCompareAddWidget(\Twig_Environment $env, AppProduct $product)
    {
        if ($this->productCompareManager->added($product->getId())) {
            return $env->render(':ecommerce/product/compare/widget:added.html.twig');
        }

        return $env->render(':ecommerce/product/compare/widget:add.html.twig', [
            'form' => $this->productFormFactory->createCompareAddFormView($product),
        ]);
    }

    /**
     * @param \Twig_Environment $environment Environment
     *
     * @return string
     */
    public function appShowCatalogsOnHomeWidget(\Twig_Environment $environment)
    {
        return $environment->render(':ecommerce:show_catalogs_on_home.html.twig', [
            'catalogs' => $this->getCatalogRepository()->getCatalogsForHomePage(),
        ]);
    }

    /**
     * @param \Twig_Environment $environment Environment
     * @param int               $catalogId   Product catalog ID
     *
     * @return string
     */
    public function appShowProductsOnHomeWidget(\Twig_Environment $environment, $catalogId)
    {
        return $environment->render(':ecommerce:show_products_on_home.html.twig', [
            'products' => $this->getProductRepository()->getProductsForHomePage($this->localeProvider->getCurrentLocale(), $catalogId),
        ]);
    }

    /**
     * @param \Twig_Environment $environment Environment
     * @param AppProduct        $product     AppProduct
     *
     * @return string
     */
    public function appShowProductInInteriorsWidget(\Twig_Environment $environment, AppProduct $product)
    {
        return $environment->render(':ecommerce:show_product_in_interiors.html.twig', [
            'interiors' => $this->getInteriorRepository()->getListForProductBuilder()->getQuery()->getResult(),
            'product' => $product,
        ]);
    }

    /**
     * @param \Twig_Environment $environment Environment
     * @param AppProduct        $product     AppProduct
     *
     * @return string
     */
    public function appShowFittingWidget(\Twig_Environment $environment, AppProduct $product)
    {
        $colors = $this->getColorRepository()->getListForProductBuilder($product)->getQuery()->getResult();
        $glasses = $this->getGlassRepository()->getListForProductBuilder($product)->getQuery()->getResult();
        $variants = $this->getVariantRepository()->getListForProductBuilder($product)->getQuery()->getResult();
        $result = array();
        $mainVariant = current($variants);

        if (empty($variants)) {
            return '';
        }

        /** @var $color Color  */
        foreach ($colors as $color) {
            $glassId = null;
            /** @var $glass Glass */
            foreach ($glasses as $glass) {
                /** @var $variant Variant */
                foreach ($variants as $variant) {
                    if ($variant->getColor() === $color && $variant->getGlass() === $glass) {
                        $result[$color->getId()][$glass->getId()] = 0;
                        if (!$glassId) {
                            $glassId = $glass->getId();
                        }
                    }

                    if ($variant->getMain()) {
                        $mainVariant = $variant;
                    }
                }
            }
            foreach ($glasses as $glass) {
                if (!isset($result[$color->getId()][$glass->getId()])) {
                    $result[$color->getId()][$glass->getId()] = $glassId;
                }
            }
        }

        return $environment->render(':ecommerce:show_fitting.html.twig', [
            'colors'       => $colors,
            'glasses'      => $glasses,
            'variants'     => $variants,
            'result'       => $result,
            'main_variant' => $mainVariant,
        ]);
    }

    /**
     * @param Catalog $catalog Catalog
     *
     * @return array
     */
    public function renderPropertiesList($catalog)
    {
        $propertyRepository = $this->getPropertyRepository();

        $properties = $propertyRepository->getByCatalogBuilder($catalog->getTreePath())->getQuery()->getResult();

        return $properties;
    }

    /**
     * @param \Twig_Environment $env         Environment
     * @param AppProduct        $product     Product
     * @param FormView          $cartAddForm Cart Form
     *
     * @return string
     */
    public function renderShowCalculator(\Twig_Environment $env, $product, $cartAddForm)
    {
        $propertyRepository = $this->getPropertyRepository();

        $properties = $propertyRepository->getListForProductBuilder($product)->getQuery()->getResult();

        if (empty($properties)) {
            return '';
        }

        return $env->render(':ecommerce:show_calculator.html.twig', [
            'product'       => $product,
            'properties'    => $properties,
            'cart_add_form' => $cartAddForm,
        ]);
    }

    /**
     * @param \Twig_Environment $env     Environment
     * @param AppProduct        $product Product
     *
     * @return string
     */
    public function renderProductLastAndNext(\Twig_Environment $env, $product)
    {
        $lastProduct = $this->getProductRepository()->getLastProduct($this->localeProvider->getCurrentLocale(), $product);
        $nextProduct = $this->getProductRepository()->getNextProduct($this->localeProvider->getCurrentLocale(), $product);

        if (!$lastProduct && !$nextProduct) {
            return '';
        }

        return $env->render(':ecommerce/product:product_last_and_next.html.twig', [
            'lastProduct' => $lastProduct,
            'nextProduct' => $nextProduct,
            'product'     => $product,
        ]);
    }

    /**
     * @param \Twig_Environment $env     Environment
     * @param AppProduct        $product Product
     *
     * @return string
     */
    public function renderFurnitureListForProduct(\Twig_Environment $env, $product)
    {
        $products = $this->getProductRepository()->getFurnitureProducts($this->localeProvider->getCurrentLocale(), $product);
        if (empty($products)) {
            return '';
        }
        $catalogs = $this->getCatalogRepository()->getFurnitureCatalogs($this->localeProvider->getCurrentLocale());

        return $env->render(':ecommerce/product:furniture_list_for_product.html.twig', [
            'catalogs' => $catalogs,
            'products' => $products,
        ]);
    }

    /**
     * @param \Twig_Environment $env     Environment
     * @param AppProduct        $product Product
     *
     * @return string
     */
    public function renderFurnitureFormsListForProduct(\Twig_Environment $env, $product)
    {
        $products = $this->getProductRepository()->getFurnitureProducts($this->localeProvider->getCurrentLocale(), $product);
        if (empty($products)) {
            return '';
        }

        $cartAddForms = [];

        foreach ($products as $product) {
            $cartAddForms[$product->getId()] = $this->cartFormFactory->createAddFormView($product);
        }

        return $env->render(':ecommerce/product:furniture_forms_list_for_product.html.twig', [
            'products' => $products,
            'forms'    => $cartAddForms,
        ]);
    }

    /**
     * @param AppProduct $product Product
     *
     * @return FormView
     */
    public function cartAddForm($product)
    {
        $form = $this->cartFormFactory->createAddFormView($product);

        return $form;
    }

    /**
     * @param \Twig_Environment $env     Environment
     * @param AppCatalog        $catalog Catalog
     *
     * @return string
     */
    public function getMinPriceForCatalog(\Twig_Environment $env, $catalog)
    {
        $minPrice = $this->getProductRepository()->getMinPrice($this->localeProvider->getCurrentLocale(), $catalog);

        return $env->render(':ecommerce/catalog:min_price_for_catalog.html.twig', [
            'min_price' => $minPrice,
        ]);
    }

    /**
     * @return AppCatalogRepository|\Doctrine\ORM\EntityRepository
     */
    private function getCatalogRepository()
    {
        return $this->em->getRepository(AppCatalog::class);
    }

    /**
     * @return AppProductRepository|\Doctrine\ORM\EntityRepository
     */
    private function getProductRepository()
    {
        return $this->em->getRepository(AppProduct::class);
    }

    /**
     * @return InteriorRepository|\Doctrine\ORM\EntityRepository
     */
    private function getInteriorRepository()
    {
        return $this->em->getRepository(Interior::class);
    }

    /**
     * @return ColorRepository|\Doctrine\ORM\EntityRepository
     */
    private function getColorRepository()
    {
        return $this->em->getRepository(Color::class);
    }

    /**
     * @return GlassRepository|\Doctrine\ORM\EntityRepository
     */
    private function getGlassRepository()
    {
        return $this->em->getRepository(Glass::class);
    }

    /**
     * @return VariantRepository|\Doctrine\ORM\EntityRepository
     */
    private function getVariantRepository()
    {
        return $this->em->getRepository(Variant::class);
    }

    /**
     * @return AppPropertyRepository|\Doctrine\ORM\EntityRepository
     */
    private function getPropertyRepository()
    {
        return $this->em->getRepository(AppProperty::class);
    }
}
