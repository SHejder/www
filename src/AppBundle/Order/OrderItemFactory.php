<?php
/**
 * @author    Alexander Volodin <mr-stanlik@yandex.ru>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Order;

use AppBundle\Entity\ECommerce\Cart\AdditionalPrice;
use AppBundle\Entity\ECommerce\Cart\AppItem;
use AppBundle\Entity\ECommerce\Order\Item;
use Darvin\ECommerceBundle\Entity\Cart\Item as CartItem;
use Darvin\ECommerceBundle\Order\OrderException;
use Darvin\ECommerceBundle\Order\OrderItemFactory as BaseOrderItemFactory;

/**
 * Order factory
 */
class OrderItemFactory extends BaseOrderItemFactory
{

    /**
     * @param CartItem $cartItem Cart item
     *
     * @return \Darvin\ECommerceBundle\Entity\Order\Item
     * @throws \Darvin\ECommerceBundle\Order\OrderException
     */
    public function createFromCartItem(CartItem $cartItem)
    {
        $orderItem = $this->createItemInstance();

        $product = $cartItem->getProduct();

        if (empty($product)) {
            throw new OrderException('Unable to create order item from cart item: cart item product is empty.');
        }

        $parameters = '';
        if ($cartItem->getVariant()) {
            $parameters .= 'Цвет: '.$cartItem->getVariant()->getColorTitle().'<br/>';
            $parameters .= 'Остекление: '.$cartItem->getVariant()->getGlassTitle().'<br/>';
        }

        /**
         * @var AdditionalPrice $additionalPrice
         */
        foreach ($cartItem->getAdditionalPrices() as $additionalPrice) {
            $countText = $additionalPrice->getCount() ? ' ('.$additionalPrice->getCount().')' : '';
            $parameters .= $additionalPrice->getTitle().': '.$additionalPrice->getValue().$countText.'</br>';
        }

        return $orderItem
            ->setProduct($cartItem->getProduct())
            ->setProductId($cartItem->getProductId())
            ->setProductPrice($cartItem->getSumPrice())
            ->setProductTitle($product->getTitle())
            ->setProductCount($cartItem->getProductCount())
            ->setParameters($parameters);
    }
}
