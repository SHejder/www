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

use Symfony\Component\Translation\TranslatorInterface;

/**
 * SMS sender
 */
class SmsSender
{
    /**
     * @var \Symfony\Component\Translation\TranslatorInterface
     */
    private $translator;

    /**
     * @var \AppBundle\Sms\SmsTransportInterface
     */
    private $transport;

    /**
     * @var int
     */
    private $messagePartMaxLength;

    /**
     * @var string
     */
    private $trimmedMessagePartSuffix;

    /**
     * @param \Symfony\Component\Translation\TranslatorInterface $translator               Translator
     * @param \AppBundle\Sms\SmsTransportInterface               $transport                SMS transport
     * @param int                                                $messagePartMaxLength     Message part max length
     * @param string                                             $trimmedMessagePartSuffix Trimmed message part suffix
     */
    public function __construct(
        TranslatorInterface $translator,
        SmsTransportInterface $transport,
        $messagePartMaxLength,
        $trimmedMessagePartSuffix
    ) {
        $this->translator = $translator;
        $this->transport = $transport;
        $this->messagePartMaxLength = $messagePartMaxLength;
        $this->trimmedMessagePartSuffix = $trimmedMessagePartSuffix;
    }

    /**
     * @param string|string[] $to      To
     * @param string          $title   Title
     * @param string|string[] $message Message
     * @param array           $options Options
     *
     * @return mixed
     */
    public function sendSms($to, $title, $message, array $options = [])
    {
        $message = (array) $message;

        foreach ($message as $key => $part) {
            $length = mb_strlen($part);

            if ($length > $this->messagePartMaxLength) {
                $message[$key] = mb_substr($part, 0, $this->messagePartMaxLength).$this->trimmedMessagePartSuffix;
            }
        }

        $message = array_merge([$this->translator->trans($title)], $message);

        return $this->transport->send($to, implode(' ', $message), $options);
    }
}
