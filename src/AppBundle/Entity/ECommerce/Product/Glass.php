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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Glass
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ECommerce\Product\GlassRepository")
 * @ORM\Table(name="ecommerce_glass")
 */
class Glass
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
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @var \AppBundle\Entity\ECommerce\Product\GlassImage
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\ECommerce\Product\GlassImage", cascade={"persist", "remove"})
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
     * @var \AppBundle\Entity\ECommerce\Product\Variant[]|\Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ECommerce\Product\Variant", mappedBy="glass", cascade={"remove"})
     */
    protected $variants;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->variants = new ArrayCollection();
        $this->enabled = true;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getTitle();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * @param \AppBundle\Entity\ECommerce\Product\GlassImage $image image
     *
     * @return Glass
     */
    public function setImage($image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\ECommerce\Product\GlassImage
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param int $position position
     *
     * @return Glass
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
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param boolean $enabled
     *
     * @return Glass
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

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
     * @return Glass
     */
    public function setVariants($variants)
    {
        $this->variants = $variants;

        return $this;
    }
}