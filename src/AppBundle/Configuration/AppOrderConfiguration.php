<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Configuration;

use Darvin\ConfigBundle\Parameter\ParameterModel;
use Darvin\OrderBundle\Configuration\Configuration;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * Order configuration
 *
 * @method string[] getSmsNotificationPhones()
 */
class AppOrderConfiguration extends Configuration
{
    /**
     * {@inheritdoc}
     */
    public function getModel()
    {
        return array_merge(parent::getModel(), [
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
        ]);
    }
}
