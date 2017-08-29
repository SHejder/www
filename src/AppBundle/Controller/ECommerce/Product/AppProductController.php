<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller\ECommerce\Product;

use AppBundle\Entity\ECommerce\Product\AppProduct;
use Darvin\ContentBundle\Controller\AbstractContentController;
use Darvin\ECommerceBundle\Entity\Product\Product;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * Product controller
 */
class AppProductController extends AbstractContentController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request      $request Request
     * @param \Darvin\ECommerceBundle\Entity\Product\Product $product Product
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showAction(Request $request, $product)
    {
        if (!$product->isEnabled()) {
            throw $this->createNotFoundException(sprintf('Product with ID "%d" is not enabled.', $product->getId()));
        }

        $productCatalog = $product->getCatalog();

        if (!$productCatalog->isEnabled()) {
            throw $this->createNotFoundException(sprintf('Product catalog with ID "%d" is not enabled.', $productCatalog->getId()));
        }

        return $this->render('DarvinECommerceBundle:Product/product:show.html.twig', [
            'product'       => $product,
            'in_cart'       => $this->getCartItemRepositoryPool()->getRepositoryForCurrentUser()->hasItem($product->getId()),
            'cart_add_form' => $this->getCartFormFactory()->createAddFormView($product)
            ,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function handleQueryBuilder(QueryBuilder $qb, $locale)
    {
        $qb
            ->addSelect('property_value')
            ->addSelect('property')
            ->addSelect('property_value_translation')
            ->addSelect('property_translation')
            ->leftJoin('o.propertyValues', 'property_value')
            ->leftJoin('property_value.property', 'property')
            ->leftJoin('property_value.translations', 'property_value_translation')
            ->leftJoin('property.translations', 'property_translation')
            ->andWhere('property_value_translation.locale = :locale OR property_value_translation.id IS NULL')
            ->andWhere('property_translation.locale = :locale OR property_translation.id IS NULL')
            ->setParameter('locale', $locale);
    }

    /**
     * {@inheritdoc}
     */
    public function getContentClass()
    {
        return AppProduct::class;
    }

    /**
     * @return \Darvin\ECommerceBundle\Form\Factory\Cart\CartFormFactory
     */
    private function getCartFormFactory()
    {
        return $this->get('darvin_ecommerce.cart.form_factory');
    }

    /**
     * @return \Darvin\ECommerceBundle\Repository\Cart\Item\CartItemRepositoryPool
     */
    private function getCartItemRepositoryPool()
    {
        return $this->get('darvin_ecommerce.cart_item.repository.pool');
    }
}
