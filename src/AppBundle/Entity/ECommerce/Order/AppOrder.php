<?php
/**
 * @author    Alexander Volodin <mr-stanlik@yandex.ru>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity\ECommerce\Order;

use Darvin\ECommerceBundle\Entity\Order\Order;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\GroupSequenceProviderInterface;

/**
 * Order
 *
 * @ORM\Entity(repositoryClass="Darvin\ECommerceBundle\Repository\Order\OrderRepository")
 * @ORM\AttributeOverrides({
 *     @ORM\AttributeOverride(name="userEmail", column=@ORM\Column(nullable = true)),
 * })
 *
 * @Assert\GroupSequenceProvider
 */
class AppOrder extends Order implements GroupSequenceProviderInterface
{
    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"App"})
     */
    protected $userFullName;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"App"})
     */
    protected $userPhone;

    /**
     * @var string
     *
     * @Assert\Email(groups={"App"})
     */
    protected $userEmail;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $userInfo;

    /**
     * {@inheritdoc}
     */
    public function getGroupSequence()
    {
        return ['App'];
    }

    /**
     * @return string
     */
    public function getUserInfo()
    {
        return $this->userInfo;
    }

    /**
     * @param string $userInfo
     */
    public function setUserInfo($userInfo)
    {
        $this->userInfo = $userInfo;
    }
}