<?php
/**
 * @author    Alexander Volodin <mr-stanlik@yandex.ru>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity\ECommerce\Cart;

use Darvin\ECommerceBundle\Entity\Cart\Item;
use Darvin\Utils\Mapping\Annotation as Darvin;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Cart item
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ECommerce\Cart\Item\AppORMCartItemRepository")
 */
class AppItem extends Item
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $variantId;

    /**
     * @var \AppBundle\Entity\ECommerce\Product\Variant
     *
     * @Darvin\CustomObject(class="AppBundle\Entity\ECommerce\Product\Variant", initPropertyValuePath="variantId")
     */
    protected $variant;

    /**
     * @var array
     */
    protected $additionalPrices;

    /**
     * AppItem constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->additionalPrices = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getVariantId()
    {
        return $this->variantId;
    }

    /**
     * @param int $variantId
     *
     * @return AppItem
     */
    public function setVariantId($variantId)
    {
        $this->variantId = $variantId;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->productId,
            $this->productHash,
            $this->productCount,
            $this->createdAt,
            $this->variantId,
            $this->additionalPrices,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->productId,
            $this->productHash,
            $this->productCount,
            $this->createdAt,
            $this->variantId,
            $this->additionalPrices
        ) = unserialize($serialized);
    }

    /**
     * @return ArrayCollection
     */
    public function getAdditionalPrices()
    {
        return $this->additionalPrices;
    }

    /**
     * @param ArrayCollection $additionalPrices
     */
    public function setAdditionalPrices($additionalPrices)
    {
        $this->additionalPrices = $additionalPrices;
    }

    /**
     * @return \AppBundle\Entity\ECommerce\Product\Variant
     */
    public function getVariant()
    {
        return $this->variant;
    }

    /**
     * @param \AppBundle\Entity\ECommerce\Product\Variant $variant
     */
    public function setVariant($variant)
    {
        $this->variant = $variant;
        $this->variantId = $variant->getId();
    }

    /**
     * @param string $name string
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        if ($this->additionalPrices->get($name)) {
            $value = $this->additionalPrices->get($name);
        } else {
            $value = null;
        }

        return $value;
    }

    /**
     * @param string $name  string
     * @param mixed  $value mixed
     */
    public function __set($name, $value)
    {
        if ($value instanceof AdditionalPrice) {
            if ($value->getMain()) {
                $this->productCount = $value->getCount();
            }
            $this->additionalPrices->set($name, $value);
        }
    }

    /**
     * @return float
     */
    public function getSumPrice()
    {
        if (empty($this->product)) {
            return null;
        }

        $sumPropertyPrices = 0;
        $propertyMainPrice = 0;
        $variantPrice = 0;

        if ($this->getVariant()) {
            $variantPrice = $this->getVariant()->getPrice();
        }

        /** @var $additionalPrice AdditionalPrice */
        foreach ($this->getAdditionalPrices() as $additionalPrice) {
            if ($additionalPrice->getMain()) {
                $propertyMainPrice = $additionalPrice->getPrice();
            } else {
                $sumPropertyPrices += (float) $additionalPrice->getPrice() * $additionalPrice->getCount();
            }
        }

        $newPrice = ((float) $this->product->getPrice() + $variantPrice + $propertyMainPrice) * $this->productCount + $sumPropertyPrices;

        return $newPrice;
    }

    /**
     * @return float
     */
    public function getPriceOneProduct()
    {
        if (empty($this->product)) {
            return null;
        }

        $sumPropertyPrices = 0;
        $propertyMainPrice = 0;
        $variantPrice = 0;

        if ($this->getVariant()) {
            $variantPrice = $this->getVariant()->getPrice();
        }

        /** @var $additionalPrice AdditionalPrice */
        foreach ($this->getAdditionalPrices() as $additionalPrice) {
            if ($additionalPrice->getMain()) {
                $propertyMainPrice = $additionalPrice->getPrice();
            } else {
                $sumPropertyPrices += (float) $additionalPrice->getPrice() * $additionalPrice->getCount();
            }
        }

        $newPrice = ((float) $this->product->getPrice() + $variantPrice + $propertyMainPrice) + $sumPropertyPrices;

        return $newPrice;
    }

    /**
     * @return int|null
     */
    public function getMainPropertyCount()
    {
        /** @var $additionalPrice AdditionalPrice */
        foreach ($this->getAdditionalPrices() as $additionalPrice) {
            if ($additionalPrice->getMain()) {
                return $additionalPrice->getCount();
            }
        }

        return null;
    }

    /**
     * @param int $count
     */
    public function setMainPropertyCount($count)
    {
        /** @var $additionalPrice AdditionalPrice */
        foreach ($this->getAdditionalPrices() as $additionalPrice) {
            if ($additionalPrice->getMain()) {
                $additionalPrice->setCount($count);
            }
        }
    }

    /**
     * @param int $value Value
     *
     * @return Item
     */
    public function increaseProductCount($value)
    {
        $this->productCount += $value;
        $this->setMainPropertyCount($this->getMainPropertyCount() + $value);

        return $this;
    }

    /**
     * @return int
     */
    public function getProductCount()
    {
        if ($this->getMainPropertyCount()) {
            return $this->getMainPropertyCount();
        }

        return $this->productCount;
    }

    /**
     * @param int $productCount productCount
     *
     * @return Item
     */
    public function setProductCount($productCount)
    {
        if ($this->getMainPropertyCount()) {
            $this->setMainPropertyCount($productCount);
        }
        $this->productCount = $productCount;

        return $this;
    }

    /**
     * @return array
     */
    public function getHashSource()
    {
        return array_merge([$this->variantId], $this->getAdditionalPrices()->map(function (AdditionalPrice $additionalPrice) {
            return $additionalPrice->getId();
        })->toArray());
    }

}
