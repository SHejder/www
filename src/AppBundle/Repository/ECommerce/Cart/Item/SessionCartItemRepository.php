<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Repository\ECommerce\Cart\Item;

use Darvin\ECommerceBundle\Repository\Cart\Item\SessionCartItemRepository as BaseSessionCartItemRepository;

/**
 * Session cart item repository
 */
class SessionCartItemRepository extends BaseSessionCartItemRepository
{
    use AppCartItemRepositoryTrait;
}
