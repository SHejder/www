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

use Darvin\ImageBundle\Entity\Image\AbstractImage;
use Darvin\ECommerceBundle\Configuration\Configuration;
use Doctrine\ORM\Mapping as ORM;

/**
 * Interior image
 *
 * @ORM\Entity
 */
class InteriorImage extends AbstractImage
{
    /**
     * {@inheritdoc}
     */
    public function getSizeGroupName()
    {
        return Configuration::IMAGE_SIZE_GROUP_NAME;
    }
}
