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

use Darvin\ECommerceBundle\Entity\Product\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * E-commerce product
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ECommerce\Product\AppProductRepository")
 */
class AppProduct extends Product
{
    /**
     * @var \AppBundle\Entity\ECommerce\Product\AppProduct[]
     *
     * @Assert\Valid
     */
    protected $translations;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\ECommerce\Product\AppProduct", inversedBy="parentsProducts")
     */
    protected $childrenProducts;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\ECommerce\Product\AppProduct", mappedBy="childrenProducts")
     */
    protected $parentsProducts;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\ECommerce\Product\AppProduct", inversedBy="parentsFurnitureProducts")
     * @ORM\JoinTable(name="app_product_app_product_furniture")
     */
    protected $childrenFurnitureProducts;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\ECommerce\Product\AppProduct", mappedBy="childrenFurnitureProducts")
     */
    protected $parentsFurnitureProducts;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $offer;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $bestseller;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $new;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $showOnHomePage;

    /**
     * @var \AppBundle\Entity\ECommerce\Product\Variant[]|\Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ECommerce\Product\Variant", mappedBy="product", cascade={"remove"})
     */
    protected $variants;

    /**
     * @var \AppBundle\Entity\ECommerce\Product\PropertyValuePrice[]|\Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ECommerce\Product\PropertyValuePrice", mappedBy="product", cascade={"persist", "remove"})
     */
    protected $propertyValuePrices;

    /**
     * @var \AppBundle\Entity\ECommerce\Product\Brand
     *
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\ECommerce\Product\Brand", inversedBy="products")
     */
    protected $brand;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $video;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();
        $this->childrenProducts = new ArrayCollection();
        $this->parentsProducts = new ArrayCollection();
        $this->childrenFurnitureProducts = new ArrayCollection();
        $this->parentsFurnitureProducts = new ArrayCollection();
        $this->variants = new ArrayCollection();
        $this->offer = false;
        $this->bestseller = false;
        $this->new = false;
        $this->showOnHomePage = false;
    }

    /**
     * {@inheritdoc}
     */
    public static function getTranslationEntityClass()
    {
        return AppProductTranslation::class;
    }

    /**
     * @param int $colorId Color ID
     * @param int $glassId Glass ID
     *
     * @return \Darvin\ImageBundle\Entity\Image\AbstractImage|null
     */
    public function findImage($colorId, $glassId)
    {
        if (!empty($colorId) && !empty($glassId)) {
            foreach ($this->variants as $variant) {
                if (null !== $variant->getImage()
                    && null !== $variant->getColor()
                    && null !== $variant->getGlass()
                    && $variant->getColor()->getId() === $colorId
                    && $variant->getGlass()->getId() === $glassId
                ) {
                    return $variant->getImage();
                }
            }
        }
        if (!$this->images->isEmpty()) {
            return $this->images->first();
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getChildrenProducts()
    {
        return $this->childrenProducts;
    }

    /**
     * @param mixed $childrenProducts
     *
     * @return AppProduct
     */
    public function setChildrenProducts($childrenProducts)
    {
        $this->childrenProducts = $childrenProducts;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getParentsProducts()
    {
        return $this->parentsProducts;
    }

    /**
     * @param mixed $parentsProducts
     *
     * @return AppProduct
     */
    public function setParentsProducts($parentsProducts)
    {
        $this->parentsProducts = $parentsProducts;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOffer()
    {
        return $this->offer;
    }

    /**
     * @param mixed $offer
     *
     * @return AppProduct
     */
    public function setOffer($offer)
    {
        $this->offer = $offer;

        return $this;
    }

    /**
     * @return bool
     */
    public function getBestseller()
    {
        return $this->bestseller;
    }

    /**
     * @param bool $bestseller
     *
     * @return AppProduct
     */
    public function setBestseller($bestseller)
    {
        $this->bestseller = $bestseller;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNew()
    {
        return $this->new;
    }

    /**
     * @param mixed $new
     *
     * @return AppProduct
     */
    public function setNew($new)
    {
        $this->new = $new;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getShowOnHomePage()
    {
        return $this->showOnHomePage;
    }

    /**
     * @param mixed $showOnHomePage
     *
     * @return AppProduct
     */
    public function setShowOnHomePage($showOnHomePage)
    {
        $this->showOnHomePage = $showOnHomePage;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVariants()
    {
        return $this->variants;
    }

    /**
     * @param mixed $variants
     *
     * @return AppProduct
     */
    public function setVariants($variants)
    {
        $this->variants = $variants;

        return $this;
    }

    /**
     * @return Brand
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param Brand $brand
     *
     * @return AppProduct
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPropertyValuePrices()
    {
        return $this->propertyValuePrices;
    }

    /**
     * @param mixed $propertyValuePrices
     *
     * @return AppProduct
     */
    public function setPropertyValuePrices($propertyValuePrices)
    {
        $this->propertyValuePrices = $propertyValuePrices;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * @param mixed $video
     *
     * @return AppProduct
     */
    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getChildrenFurnitureProducts()
    {
        return $this->childrenFurnitureProducts;
    }

    /**
     * @param ArrayCollection $childrenFurnitureProducts
     *
     * @return AppProduct
     */
    public function setChildrenFurnitureProducts($childrenFurnitureProducts)
    {
        $this->childrenFurnitureProducts = $childrenFurnitureProducts;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getParentsFurnitureProducts()
    {
        return $this->parentsFurnitureProducts;
    }

    /**
     * @param ArrayCollection $parentsFurnitureProducts
     *
     * @return AppProduct
     */
    public function setParentsFurnitureProducts($parentsFurnitureProducts)
    {
        $this->parentsFurnitureProducts = $parentsFurnitureProducts;

        return $this;
    }
}
