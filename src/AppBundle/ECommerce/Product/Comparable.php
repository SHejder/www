<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\ECommerce\Product;

/**
 * Comparable
 */
class Comparable
{
    /**
     * @var int
     */
    private $productId;

    /**
     * @var int
     */
    private $colorId;

    /**
     * @var int
     */
    private $glassId;

    /**
     * @param int $productId Product ID
     */
    public function __construct($productId)
    {
        $this->setProductId($productId);
    }

    /**
     * @return int
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param int $productId productId
     *
     * @return Comparable
     */
    public function setProductId($productId)
    {
        $this->productId = (int) $productId;

        return $this;
    }

    /**
     * @return int
     */
    public function getColorId()
    {
        return $this->colorId;
    }

    /**
     * @param int $colorId colorId
     *
     * @return Comparable
     */
    public function setColorId($colorId)
    {
        $this->colorId = (int) $colorId;

        return $this;
    }

    /**
     * @return int
     */
    public function getGlassId()
    {
        return $this->glassId;
    }

    /**
     * @param int $glassId glassId
     *
     * @return Comparable
     */
    public function setGlassId($glassId)
    {
        $this->glassId = (int) $glassId;

        return $this;
    }
}
