<?php
/**
 * @author    Alexander Volodin <mr-stanlik@yandex.ru>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Provider;

use Darvin\BannerBundle\Type\BannerTypeProviderInterface;

/** Banner type provider */
class BannerTypeProvider implements BannerTypeProviderInterface
{
    /**
     * @return array
     */
    public function getBannerTypes()
    {
        return [
            'offers' => 'Акции и спецпредложения',
            'partners' => 'Партнеры–производители',
            'certificates' => 'Сертификаты',
            ];
    }
}