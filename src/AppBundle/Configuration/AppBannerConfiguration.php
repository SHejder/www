<?php
/**
 * @author    Alexander Volodin <mr-stanlik@yandex.ru>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Configuration;

use Darvin\ConfigBundle\Configuration\AbstractConfiguration;
use Darvin\ConfigBundle\Parameter\ParameterModel;
use Darvin\ImageBundle\Configuration\ImageConfigurationInterface;
use Darvin\ImageBundle\Form\Type\SizeType;
use Darvin\ImageBundle\Size\Size;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Darvin\BannerBundle\Configuration\Configuration as BannerConfiguration;

/**
 * Configuration
 *
 * @method int   getAdminLatestOrdersCount()
 * @method array getNotificationEmails()
 * @method int   getOrdersPerPage()
 * @method int   getProductsPerPage()
 */
class AppBannerConfiguration extends BannerConfiguration
{
    const IMAGE_SIZE_GROUP_NAME = 'banners';

    /**
     * {@inheritdoc}
     */
    public function getModel()
    {
        return [
            new ParameterModel(
                'image_sizes',
                ParameterModel::TYPE_ARRAY,
                [
                    'banner_image_show_offers' => new Size('banner_image_show_offers', 534, 388),
                    'banner_image_show_partners' => new Size('banner_image_show_partners', 176, 80),
                    'banner_image_show_certificates' => new Size('banner_image_show_certificates', 203, 255),
                ],
                [
                    'form' => [
                        'options' => [
                            'entry_type'    => SizeType::class,
                            'entry_options' => [
                                'size_group' => $this->getImageSizeGroupName(),
                            ],
                        ],
                    ],
                ]
            ),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getImageSizes()
    {
        return $this->__call(__FUNCTION__);
    }

    /**
     * {@inheritdoc}
     */
    public function isImageSizesGlobal()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getImageSizeGroupName()
    {
        return self::IMAGE_SIZE_GROUP_NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_banner';
    }
}
