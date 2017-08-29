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

use AppBundle\Entity\ECommerce\Product\AppProduct;
use AppBundle\Entity\ECommerce\Product\AppProperty;
use Darvin\ECommerceBundle\Entity\Cart\Item;
use Darvin\ECommerceBundle\Entity\Product\Product;
use Darvin\Utils\Mapping\Annotation as Darvin;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Additional Price
 */
class AdditionalPrice implements \Serializable
{
    /**
     * @var string $id
     */
    protected $id;

    /**
     * @var string $price
     */
    protected $price;

    /**
     * @var string $value
     */
    protected $value;

    /**
     * @var string $title
     */
    protected $title;

    /**
     * @var int $count
     */
    protected $count;

    /**
     * @var AppProperty $property
     */
    protected $property;

    /**
     * @var AppProduct $product
     */
    protected $product;

    /**
     * @var bool $main
     */
    protected $main;

    /**
     * AdditionalPrice constructor
     */
    public function __construct()
    {
        $this->price = 0;
        $this->count = 0;
        $this->main = false;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param string $value value
     */
    public function setValueAndPrice($value)
    {
        $array = explode(':', $value);

        if (isset($array[1]) && $array[2]) {
            $this->id = $array[0];
            $this->price = $array[1];
            $this->value = base64_decode($array[2]);
        }
    }

    /**
     * @return null
     */
    public function getValueAndPrice()
    {
        return "{$this->id}:{$this->price}:{$this->value}";
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            $this->title,
            $this->value,
            $this->price,
            $this->count,
            $this->main,
            $this->id,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list(
            $this->title,
            $this->value,
            $this->price,
            $this->count,
            $this->main,
            $this->id
            ) = unserialize($serialized);
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
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return AppProperty
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param AppProperty $property
     */
    public function setProperty($property)
    {
        $this->property = $property;
    }

    /**
     * @return AppProduct
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param AppProduct $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return bool
     */
    public function getMain()
    {
        return $this->main;
    }

    /**
     * @param bool $main
     */
    public function setMain($main)
    {
        $this->main = $main;
    }
}
