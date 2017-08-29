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
use Darvin\ImageBundle\Form\Type\SizeType;
use Darvin\ImageBundle\Size\Size;
use Darvin\PublicationsBundle\Configuration\Configuration as DefaultConfiguration;

class AppPublicationsConfiguration extends DefaultConfiguration
{

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
                    'publication_list_articles'    => new Size('publication_list_articles', 297, 160),
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
            new ParameterModel('publications_per_page', ParameterModel::TYPE_INTEGER, 10),
        ];
    }
}
