<?php
/**
 * @author    Alexander Volodin <mr-stanlik@yandex.ru>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Repository\ECommerce\Cart\Item;

use Darvin\ContentBundle\Translatable\TranslationJoinerInterface;
use Darvin\ECommerceBundle\Entity\Cart\Item;
use Darvin\ECommerceBundle\Entity\Product\Product;
use Darvin\ECommerceBundle\Repository\Cart\Item\CartItemRepositoryTrait;
use Darvin\ECommerceBundle\Repository\RepositoryException;
use Darvin\Utils\CustomObject\CustomObjectLoaderInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Cart item repository trait
 */
trait AppCartItemRepositoryTrait
{
    /**
     * @return float
     */
    public function getTotalPrice()
    {
        $price = 0.0;

        foreach ($this->getItems() as $item) {
            $price += (float) $item->getSumPrice();
        }

        return $price;
    }
}
