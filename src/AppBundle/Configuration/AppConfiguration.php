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

use Darvin\AdminBundle\Form\Type\CKEditorCompactType;
use Darvin\ConfigBundle\Configuration\AbstractConfiguration;
use Darvin\ConfigBundle\Parameter\ParameterModel;
use Darvin\ImageBundle\Configuration\ImageConfigurationInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

/**
 * App config
 *
 * @method string   getTopPhone()
 * @method string   getTopWorkingTime()
 * @method string   getFooterEmail()
 * @method string   getFooterAddress()
 * @method string   getFooterPhones()
 */

class AppConfiguration extends AbstractConfiguration implements ImageConfigurationInterface
{
    const IMAGE_SIZE_GROUP_NAME = 'app';

    /**
     * @return array
     */
    public function getModel()
    {
        return array(
            new ParameterModel('top_phone', ParameterModel::TYPE_STRING, null, [
                'form' => [
                    'type' => CKEditorCompactType::class,
                ],
            ]),
            new ParameterModel('footer_phones', ParameterModel::TYPE_STRING, null, [
                'form' => [
                    'type' => CKEditorCompactType::class,
                ]
            ]),
            new ParameterModel('top_working_time', ParameterModel::TYPE_STRING, null, [
                'form' => [
                    'type' => CKEditorCompactType::class,
                ],

            ]),
            new ParameterModel('footer_working_time', ParameterModel::TYPE_STRING, null, [
                'form' => [
                    'type' => CKEditorCompactType::class,
                ],
            ]),
            new ParameterModel('footer_email', ParameterModel::TYPE_STRING, null, [
                'options' => [
                    'entry_type'    => EmailType::class,
                ],
            ]),
            new ParameterModel('footer_address', ParameterModel::TYPE_STRING, null, []),
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app';
    }

    /**
     * @return \Darvin\ImageBundle\Size\Size[]
     */
    public function getImageSizes()
    {
        return $this->__call(__FUNCTION__);
    }

    /**
     * @return bool
     */
    public function isImageSizesGlobal()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getImageSizeGroupName()
    {
        return self::IMAGE_SIZE_GROUP_NAME;
    }
}
