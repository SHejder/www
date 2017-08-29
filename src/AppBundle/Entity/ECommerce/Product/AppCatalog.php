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

use Darvin\ECommerceBundle\Entity\Product\Catalog;
use Doctrine\ORM\Mapping as ORM;

/**
 * Product catalog
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ECommerce\Product\AppCatalogRepository")
 */
class AppCatalog extends Catalog
{
    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $showOnHomePage;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $furniture;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();
        $this->showOnHomePage = false;
    }

    /**
     * @return bool
     */
    public function getShowOnHomePage()
    {
        return $this->showOnHomePage;
    }

    /**
     * @param bool $showOnHomePage
     *
     * @return AppCatalog
     */
    public function setShowOnHomePage($showOnHomePage)
    {
        $this->showOnHomePage = $showOnHomePage;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFurniture()
    {
        return $this->furniture;
    }

    /**
     * @param mixed $furniture
     */
    public function setFurniture($furniture)
    {
        $this->furniture = $furniture;
    }
}
