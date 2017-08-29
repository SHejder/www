<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller\ECommerce\Product;

use AppBundle\ECommerce\Product\Comparable;
use AppBundle\ECommerce\Product\CompareManagerException;
use AppBundle\Entity\ECommerce\Product\AppCatalog;
use AppBundle\Entity\ECommerce\Product\AppProduct;
use AppBundle\Entity\ECommerce\Product\AppProperty;
use Darvin\ECommerceBundle\Entity\Product\Property;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

/**
 * E-commerce product compare controller
 *
 * @Route(path="/products/compare")
 */
class CompareController extends Controller
{
    /**
     * @Route(name="app_ecommerce_product_compare_add", path="/add/{id}", requirements={"id"="\d+"}, methods={"post"})
     *
     * @param \Symfony\Component\HttpFoundation\Request      $request Request
     * @param \AppBundle\Entity\ECommerce\Product\AppProduct $product Product
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request, AppProduct $product)
    {
        $redirectUrl = $request->headers->get('referer', $this->generateUrl('darvin_content_content_show', [
            'slug' => $product->getSlug(),
        ]));

        $comparable = new Comparable($product->getId());

        $form = $this->getProductFormFactory()->createCompareAddForm($product, $comparable)->handleRequest($request);

        if (!$form->isValid()) {
            $message = implode(PHP_EOL, array_map(function (FormError $error) {
                return $error->getMessage();
            }, iterator_to_array($form->getErrors(true))));

            $this->addFlash('error', $message);

            return $this->redirect($redirectUrl);
        }
        try {
            $this->getProductCompareManager()->add($comparable);
        } catch (CompareManagerException $ex) {
            throw $this->createNotFoundException($ex->getMessage(), $ex);
        }

        $this->addFlash('success', 'Товар добавлен к сравнению');

        return $this->redirect($redirectUrl);
    }

    /**
     * @Route(name="app_ecommerce_product_compare_delete", path="/delete/{id}", requirements={"id"="\d+"}, methods={"post"})
     *
     * @param \Symfony\Component\HttpFoundation\Request      $request Request
     * @param \AppBundle\Entity\ECommerce\Product\AppProduct $product Product
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request, AppProduct $product)
    {
        $redirectUrl = $request->headers->get('referer', $this->generateUrl('app_ecommerce_product_compare'));

        $form = $this->getProductFormFactory()->createCompareDeleteForm($product)->handleRequest($request);

        if (!$form->isValid()) {
            $message = implode(PHP_EOL, array_map(function (FormError $error) {
                return $error->getMessage();
            }, iterator_to_array($form->getErrors(true))));

            $this->addFlash('error', $message);

            return $this->redirect($redirectUrl);
        }
        try {
            $this->getProductCompareManager()->delete($product->getId());
        } catch (CompareManagerException $ex) {
            throw $this->createNotFoundException($ex->getMessage(), $ex);
        }

        $this->addFlash('success', 'Товар удален из сравнения');

        return $this->redirect($redirectUrl);
    }

    /**
     * @Route(name="app_ecommerce_product_compare_diff", path="/diff/{catalogId}", defaults={"diff"=true, "catalogId"=null}, methods={"get"})
     * @Route(name="app_ecommerce_product_compare", path="/{catalogId}", defaults={"diff"=false, "catalogId"=null}, methods={"get"})
     * @ParamConverter(name="currentCatalog", class="AppBundle\Entity\ECommerce\Product\AppCatalog", options={"mapping": {"catalogId": "id"}})
     *
     * @param \Symfony\Component\HttpFoundation\Request      $request        Request
     * @param bool                                           $diff           Whether to show difference
     * @param \AppBundle\Entity\ECommerce\Product\AppCatalog $currentCatalog Current product catalog
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, $diff, AppCatalog $currentCatalog = null)
    {
        $comparables = $this->getProductCompareManager()->getAddedComparables();

        /** @var \AppBundle\Entity\ECommerce\Product\AppCatalog[] $catalogs */
        $catalogs = $productCounts = $propertyValues = [];
        /** @var \AppBundle\Entity\ECommerce\Product\AppProduct[] $products */
        $products = [];
        /** @var \AppBundle\Entity\ECommerce\Product\AppProperty[] $properties */
        $properties = [];
        $showBrands = $showPrices = true;

        if (!empty($comparables)) {
            $productIds = array_keys($comparables);

            $rows = $this->getProductCatalogRepository()->getForProductCompareBuilder($productIds)->getQuery()->getResult();

            $catalogs      = array_column($rows, 'catalog');
            $productCounts = array_column($rows, 'product_count');

            if (empty($currentCatalog) && !empty($catalogs)) {
                $currentCatalog = $catalogs[0];
            }
            if (!empty($currentCatalog)) {
                $products = iterator_to_array((new Paginator(
                    $this->getProductRepository()->getForCompareBuilder($request->getLocale(), $productIds, $currentCatalog->getId())->getQuery()
                ))->getIterator());

                if (1 === count($products)) {
                    $diff = false;
                }

                $properties = $currentCatalog->getProductProperties()->filter(function (AppProperty $property) {
                    return !$property->isChoiceWithPrice();
                })->toArray();

                $propertyIds = array_map(function (AppProperty $property) {
                    return $property->getId();
                }, $properties);

                $brandIds = $prices = [];
                $samePropertyValues = array_fill_keys($propertyIds, []);

                foreach ($products as $product) {
                    if ($diff) {
                        $brandIds[] = null !== $product->getBrand() ? $product->getBrand()->getId() : null;
                        $prices[]   = $product->getDiscountPrice();
                    }

                    $propertyValues[$product->getId()] = array_fill_keys($propertyIds, null);

                    foreach ($product->getPropertyValues() as $propertyValue) {
                        $property = $propertyValue->getProperty();

                        if (!array_key_exists($property->getId(), $propertyValues[$product->getId()])) {
                            continue;
                        }

                        $propertyValues[$product->getId()][$property->getId()] = $propertyValue;

                        if ($diff) {
                            $value = Property::TYPE_STRING === $property->getType()
                                ? $propertyValue->getStringValue()
                                : $propertyValue->getValue();
                            $samePropertyValues[$property->getId()][] = is_array($value) ? implode(',', $value) : $value;
                        }
                    }
                }
                if ($diff) {
                    $showBrands = count(array_unique($brandIds)) > 1;
                    $showPrices = count(array_unique($prices)) > 1;

                    foreach ($samePropertyValues as $propertyId => $values) {
                        if (1 !== count(array_unique($values))) {
                            unset($samePropertyValues[$propertyId]);
                        }
                    }
                    foreach ($properties as $key => $property) {
                        if (isset($samePropertyValues[$property->getId()])) {
                            unset($properties[$key]);
                        }
                    }
                    foreach ($propertyValues as $productId => $productPropertyValues) {
                        foreach ($productPropertyValues as $propertyId => $propertyValue) {
                            if (isset($samePropertyValues[$propertyId])) {
                                unset($propertyValues[$productId][$propertyId]);
                            }
                        }
                    }
                }
            }
        }

        return $this->render(':ecommerce/product/compare:index.html.twig', [
            'catalogs'        => $catalogs,
            'comparables'     => $comparables,
            'current_catalog' => $currentCatalog,
            'delete_forms'    => $this->getProductFormFactory()->createCompareDeleteFormViews($products),
            'product_counts'  => $productCounts,
            'products'        => $products,
            'properties'      => $properties,
            'property_values' => $propertyValues,
            'show_brands'     => $showBrands,
            'show_prices'     => $showPrices,
        ]);
    }

    /**
     * @return \AppBundle\Repository\ECommerce\Product\AppCatalogRepository
     */
    private function getProductCatalogRepository()
    {
        return $this->getDoctrine()->getRepository(AppCatalog::class);
    }

    /**
     * @return \AppBundle\ECommerce\Product\CompareManager
     */
    private function getProductCompareManager()
    {
        return $this->get('app.ecommerce.product.compare_manager');
    }

    /**
     * @return \AppBundle\Form\Factory\ECommerce\Product\ProductFormFactory
     */
    private function getProductFormFactory()
    {
        return $this->get('app.ecommerce.product.form_factory');
    }

    /**
     * @return \AppBundle\Repository\ECommerce\Product\AppProductRepository
     */
    private function getProductRepository()
    {
        return $this->getDoctrine()->getRepository(AppProduct::class);
    }
}
