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
 * @method string   getTopPhone1()
 * @method string   getTopPhone2()
 * @method string   getTopWorkingTime()
 * @method string   getFooterPhone1()
 * @method string   getFooterPhone2()
 * @method string   getFooterWorkingTime()
 * @method string   getFooterEmail()
 * @method string   getFooterAddress()
 * @method string   getViber()
 * @method string   getWhatsup()
 * @method string   getVk()
 * @method string   getTwitter()
 * @method string   getFacebook()
 * @method string   getGooglePlus()
 * @method string   getInstagram()
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
            new ParameterModel('top_phone1', ParameterModel::TYPE_STRING, null, []),
            new ParameterModel('top_phone2', ParameterModel::TYPE_STRING, null, []),
            new ParameterModel('top_working_time', ParameterModel::TYPE_STRING, null, []),
            new ParameterModel('footer_phone1', ParameterModel::TYPE_STRING, null, []),
            new ParameterModel('footer_phone2', ParameterModel::TYPE_STRING, null, []),
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
            new ParameterModel('footer_address', ParameterModel::TYPE_STRING, null, [
                'form' => [
                    'type' => CKEditorCompactType::class,
                ],
            ]),
            new ParameterModel('our_shops', ParameterModel::TYPE_STRING, null, [
                'form' => [
                    'type' => CKEditorCompactType::class,
                ],
            ]),
            new ParameterModel('how_we_work', ParameterModel::TYPE_STRING, null, [
                'form' => [
                    'type' => CKEditorCompactType::class,
                ],
            ]),
            new ParameterModel('additional_guarantee', ParameterModel::TYPE_STRING, null, [
                'form' => [
                    'type' => CKEditorCompactType::class,
                ],
            ]),
            new ParameterModel('advantages', ParameterModel::TYPE_STRING, null, [
                'form' => [
                    'type' => CKEditorCompactType::class,
                ],
            ]),
            new ParameterModel('viber', ParameterModel::TYPE_STRING, null, []),
            new ParameterModel('whatsapp', ParameterModel::TYPE_STRING, null, []),
            new ParameterModel('vk', ParameterModel::TYPE_STRING, null, []),
            new ParameterModel('twitter', ParameterModel::TYPE_STRING, null, []),
            new ParameterModel('facebook', ParameterModel::TYPE_STRING, null, []),
            new ParameterModel('google_plus', ParameterModel::TYPE_STRING, null, []),
            new ParameterModel('instagram', ParameterModel::TYPE_STRING, null, []),
        );
    }

    /**
     * @return string
     */
    public function getCanonizedTopPhone1()
    {
        return preg_replace('/[^+0-9]+/', '', $this->getTopPhone1());
    }

    /**
     * @return string
     */
    public function getCanonizedTopPhone2()
    {
        return preg_replace('/[^+0-9]+/', '', $this->getTopPhone2());
    }

    /**
     * @return string
     */
    public function getCanonizedFooterPhone1()
    {
        return preg_replace('/[^+0-9]+/', '', $this->getFooterPhone1());
    }

    /**
     * @return string
     */
    public function getCanonizedFooterPhone2()
    {
        return preg_replace('/[^+0-9]+/', '', $this->getFooterPhone2());
    }

    /**
     * @return string[]
     */
    public function getSocialNetworkUrls()
    {
        $urls = [
            $this->getVk(),
            $this->getTwitter(),
            $this->getFacebook(),
            $this->getGooglePlus(),
            $this->getInstagram(),
        ];

        foreach ($urls as $key => $url) {
            if (empty($url)) {
                unset($urls[$key]);
            }
        }

        return $urls;
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
