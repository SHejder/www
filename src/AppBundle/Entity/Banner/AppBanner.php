<?php
/**
 * @author    Alexander Volodin <mr-stanlik@yandex.ru>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity\Banner;

use Darvin\BannerBundle\Entity\Banner;
use Doctrine\ORM\Mapping as ORM;

/**
 * Banner
 *
 * @ORM\Entity(repositoryClass="Darvin\BannerBundle\Repository\BannerRepository")
 */
class AppBanner extends Banner
{
    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $noindex;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();
        $this->noindex = false;
    }

    /**
     * @return bool
     */
    public function getNoindex()
    {
        return $this->noindex;
    }

    /**
     * @param bool $noindex
     *
     * @return AppBanner
     */
    public function setNoindex($noindex)
    {
        $this->noindex = $noindex;

        return $this;
    }
}
