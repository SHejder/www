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

use Darvin\ECommerceBundle\Entity\Product\Property;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Product property
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ECommerce\Product\AppPropertyRepository")
 *
 */
class AppProperty extends Property
{

    const TYPE_CHOICE_WITH_PRICE = 'choice_with_price';

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     *
     * @Gedmo\SortablePosition
     */
    protected $position;

    /**
     * @var \Darvin\ECommerceBundle\Entity\Product\Catalog
     *
     * @ORM\ManyToOne(targetEntity="Darvin\ECommerceBundle\Entity\Product\CatalogInterface", inversedBy="productProperties")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Gedmo\SortableGroup
     */
    protected $catalog;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $main;

    /**
     * @var array
     */
    protected static $types = [
        'ecommerce_product_property.entity.types.string'  => parent::TYPE_STRING,
        'ecommerce_product_property.entity.types.boolean' => parent::TYPE_BOOLEAN,
        'ecommerce_product_property.entity.types.choice'  => parent::TYPE_CHOICE,
        'ecommerce_product_property.entity.types.choice_with_price'  => self::TYPE_CHOICE_WITH_PRICE,
    ];

    /**
     * @var \AppBundle\Entity\ECommerce\Product\AppPropertyValue[]|\Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ECommerce\Product\AppPropertyValue", mappedBy="property", cascade={"remove"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $appValues;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $brandDependent;

    /**
     * @return bool
     */
    public function isChoiceWithPrice()
    {
        return self::TYPE_CHOICE_WITH_PRICE === $this->type;
    }

    /**
     * @return array
     */
    public static function getTypes()
    {
        return self::$types;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->appValues = new ArrayCollection();
        $this->main = $this->brandDependent = false;
    }

    /**
     * @return AppPropertyValue[]|Collection
     */
    public function getAppValues()
    {
        return $this->appValues;
    }

    /**
     * @param AppPropertyValue[]|Collection $appValues
     */
    public function setAppValues($appValues)
    {
        $this->appValues = $appValues;
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return in_array($this->type, self::$types) ? array_search($this->type, self::$types) : $this->type;
    }

    /**
     * @param int $position position
     *
     * @return AppProperty
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
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
     *
     * @return AppProperty
     */
    public function setMain($main)
    {
        $this->main = $main;

        return $this;
    }

    /**
     * @return bool
     */
    public function isBrandDependent()
    {
        return $this->brandDependent;
    }

    /**
     * @param bool $brandDependent brandDependent
     *
     * @return AppProperty
     */
    public function setBrandDependent($brandDependent)
    {
        $this->brandDependent = $brandDependent;

        return $this;
    }
}
