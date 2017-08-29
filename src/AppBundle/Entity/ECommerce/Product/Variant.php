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
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Product variant
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ECommerce\Product\VariantRepository")
 * @ORM\Table(name="ecommerce_product_variant")
 */
class Variant
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
     * @var \AppBundle\Entity\ECommerce\Product\AppProduct|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ECommerce\Product\AppProduct", inversedBy="variants")
     * @ORM\JoinColumn(nullable=false)
     *
     */
    protected $product;

    /**
     * @var \AppBundle\Entity\ECommerce\Product\Color|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ECommerce\Product\Color", inversedBy="variants")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $color;

    /**
     * @var \AppBundle\Entity\ECommerce\Product\Glass|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ECommerce\Product\Glass", inversedBy="variants")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $glass;

    /**
     * @var string
     *
     * @ORM\Column(type="decimal", scale=2)
     *
     * @Assert\NotBlank
     */
    protected $price;

    /**
     * @var \AppBundle\Entity\ECommerce\Product\VariantImage
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\ECommerce\Product\VariantImage", cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     *
     * @Assert\Valid
     */
    protected $image;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $enabled;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $main;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->price = 0.00;
        $this->enabled = true;
        $this->main = false;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Variant
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return AppProduct|null
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param AppProduct|null $product
     *
     * @return Variant
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return Color|null
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param Color|null $color
     *
     * @return Variant
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return Glass|null
     */
    public function getGlass()
    {
        return $this->glass;
    }

    /**
     * @param Glass|null $glass
     *
     * @return Variant
     */
    public function setGlass($glass)
    {
        $this->glass = $glass;

        return $this;
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
     *
     * @return Variant
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @param \AppBundle\Entity\ECommerce\Product\VariantImage $image image
     *
     * @return Variant
     */
    public function setImage($image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\ECommerce\Product\VariantImage
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getTitle();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return (string) 'Цвет: '.$this->getColor()->getTitle().', Остекление: '.$this->getGlass()->getTitle();
    }

    /**
     * @return string
     */
    public function getColorTitle()
    {
        return (string) $this->getColor()->getTitle();
    }

    /**
     * @return string
     */
    public function getGlassTitle()
    {
        return (string) $this->getGlass()->getTitle();
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return (string) $this->getColor()->getPosition().(string) $this->getGlass()->getPosition();
    }

    /**
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param boolean $enabled
     *
     * @return Variant
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMain()
    {
        return $this->main;
    }

    /**
     * @param mixed $main
     *
     * @return Variant
     */
    public function setMain($main)
    {
        $this->main = $main;

        return $this;
    }
}
