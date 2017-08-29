<?php
/**
 * @author    Alexander Volodin <mr-stanlik@yandex.ru>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity\ECommerce\Product;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product property value
 *
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\Table(name="ecommerce_product_property_value_price")
 *
 * @method string getStringValue()
 */
class PropertyValuePrice
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", unique=true)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Id
     */
    protected $id;

    /**
     * @var \AppBundle\Entity\ECommerce\Product\AppProduct
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ECommerce\Product\AppProduct", inversedBy="propertyValuePrices")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $product;

    /**
     * @var \AppBundle\Entity\ECommerce\Product\AppPropertyValue
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ECommerce\Product\AppPropertyValue", inversedBy="propertyPrices")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $propertyValue;

    /**
     * @var string
     *
     * @ORM\Column(type="decimal", scale=2)
     *
     * @Assert\NotBlank
     */
    protected $price;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $showChoice;

    /**
     * PropertyValuePrice constructor.
     */
    public function __construct()
    {
        $this->price = 0.00;
        $this->showChoice = false;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return AppProduct
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param \AppBundle\Entity\ECommerce\Product\AppProduct $product product
     *
     * @return PropertyValuePrice
     */
    public function setProduct(AppProduct $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\ECommerce\Product\AppPropertyValue
     */
    public function getPropertyValue()
    {
        return $this->propertyValue;
    }

    /**
     * @param \AppBundle\Entity\ECommerce\Product\AppPropertyValue $propertyValue
     */
    public function setPropertyValue($propertyValue)
    {
        $this->propertyValue = $propertyValue;
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param string $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return bool
     */
    public function getShowChoice()
    {
        return $this->showChoice;
    }

    /**
     * @param bool $showChoice
     */
    public function setShowChoice($showChoice)
    {
        $this->showChoice = $showChoice;
    }
}
