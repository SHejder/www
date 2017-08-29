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

use Darvin\ECommerceBundle\Entity\Product\ProductTranslation;
use Doctrine\ORM\Mapping as ORM;

/**
 * E-commerce product translation
 *
 * @ORM\Entity
 *
 * @method \AppBundle\Entity\ECommerce\Product\AppProduct getTranslatable()
 */
class AppProductTranslation extends ProductTranslation
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public static function getTranslatableEntityClass()
    {
        return AppProduct::class;
    }
}
