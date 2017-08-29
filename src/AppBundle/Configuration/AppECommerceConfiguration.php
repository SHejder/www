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

use Darvin\ConfigBundle\Parameter\ParameterModel;
use Darvin\ECommerceBundle\Configuration\Configuration as DefaultConfiguration;
use Darvin\ImageBundle\Form\Type\SizeType;
use Darvin\ImageBundle\Size\Size;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * Configuration
 *
 * @method int      getAdminLatestOrdersCount()
 * @method array    getNotificationEmails()
 * @method string[] getSmsNotificationPhones()
 * @method int      getOrdersPerPage()
 * @method int      getProductsPerPage()
 * @method int      getFeaturedProducts()
 */
class AppECommerceConfiguration extends DefaultConfiguration
{
    const IMAGE_SIZE_GROUP_NAME = 'ecommerce';

    /**
     * {@inheritdoc}
     */
    public function getModel()
    {
        return [
            new ParameterModel('admin_latest_orders_count', ParameterModel::TYPE_INTEGER, 3),
            new ParameterModel(
                'image_sizes',
                ParameterModel::TYPE_ARRAY,
                [
                    'catalog_image_catalog_show_children' => new Size('catalog_image_catalog_show_children', 128, 128),
                    'catalog_image_catalog_show'          => new Size('catalog_image_catalog_show', 329, 274),
                    'featured_products'                   => new Size('featured_products', 127, 306),
                    'product_compare'                     => new Size('product_compare', 127, 306),
                    'product_list'                        => new Size('product_list', 128, 128),
                    'product_show'                        => new Size('product_show', 256, 256),
                    'interior_list'                       => new Size('interior_list', 128, 128),
                    'interior_show'                       => new Size('interior_show', 256, 256),
                    'product_show_furniture'              => new Size('product_show_furniture', 500, 500),
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
            new ParameterModel('notification_emails', ParameterModel::TYPE_ARRAY, [], [
                'form' => [
                    'options' => [
                        'entry_type'   => EmailType::class,
                        'allow_add'    => true,
                        'allow_delete' => true,
                    ],
                ],
            ]),
            new ParameterModel('sms_notification_phones', ParameterModel::TYPE_ARRAY, [], [
                'form' => [
                    'options' => [
                        'allow_add'     => true,
                        'allow_delete'  => true,
                        'entry_options' => [
                            'constraints' => [
                                new Regex('/^\d+$/'),
                                new Length([
                                    'min' => 11,
                                    'max' => 11,
                                ]),
                            ],
                        ],
                    ],
                ],
            ]),
            new ParameterModel('orders_per_page', ParameterModel::TYPE_INTEGER, 10),
            new ParameterModel('products_per_page', ParameterModel::TYPE_INTEGER, 10),
            new ParameterModel('featured_products', ParameterModel::TYPE_INTEGER, 10),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'darvin_ecommerce';
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
}
