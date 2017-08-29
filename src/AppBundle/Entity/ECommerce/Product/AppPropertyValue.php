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
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Product property value
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ECommerce\Product\AppPropertyValueRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\Table(name="ecommerce_product_app_property_value")
 *
 * @method string getStringValue()
 */
class AppPropertyValue
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
     * @var int
     *
     * @ORM\Column(type="integer")
     *
     * @Gedmo\SortablePosition
     */
    protected $position;

    /**
     * @var \AppBundle\Entity\ECommerce\Product\PropertyValuePrice[]|\Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ECommerce\Product\PropertyValuePrice", mappedBy="propertyValue", cascade={"remove"})
     */
    protected $propertyPrices;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $value;

    /**
     * @var \AppBundle\Entity\ECommerce\Product\AppProperty
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ECommerce\Product\AppProperty", inversedBy="appValues")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Gedmo\SortableGroup
     */
    protected $property;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->propertyPrices = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getValue();
    }

    /**
     * @return PropertyValuePrice[]|\Doctrine\Common\Collections\Collection
     */
    public function getPropertyPrices()
    {
        return $this->propertyPrices;
    }

    /**
     * @param PropertyValuePrice[]|\Doctrine\Common\Collections\Collection $propertyPrices
     */
    public function setPropertyPrices($propertyPrices)
    {
        $this->propertyPrices = $propertyPrices;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return AppPropertyValue
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Property
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param AppProperty $property property
     *
     * @return AppPropertyValue
     */
    public function setProperty(AppProperty $property = null)
    {
        $this->property = $property;

        return $this;
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
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     *
     * @return AppPropertyValue
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }
}
