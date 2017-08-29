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

use Darvin\ECommerceBundle\Repository\Cart\Item\ORMCartItemRepository;
use Darvin\ECommerceBundle\Repository\RepositoryException;
use Darvin\UserBundle\Entity\BaseUser;
use Darvin\UserBundle\User\UserManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * ORM cart item repository
 */
class AppORMCartItemRepository extends ORMCartItemRepository
{
    use AppCartItemRepositoryTrait;

    /**
     * {@inheritdoc}
     */
    public function __construct($em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
    }
}
