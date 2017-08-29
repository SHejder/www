<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Sms;

/**
 * SMS transport
 */
interface SmsTransportInterface
{
    /**
     * @param string|string[] $to      To
     * @param string          $message Message
     * @param array           $options Options
     *
     * @return mixed
     * @throws \AppBundle\Sms\SmsException
     */
    public function send($to, $message, array $options = []);
}
