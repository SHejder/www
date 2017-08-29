<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Sms\SmsRu\Response;

/**
 * sms.ru SMS transport response
 */
class Response
{
    /**
     * @var string
     *
     * @required
     */
    private $status;

    /**
     * @var int
     *
     * @required
     */
    private $status_code;

    /**
     * @var string
     */
    private $status_text;

    /**
     * @var \AppBundle\Sms\SmsRu\Response\Sms[]
     */
    private $sms;

    /**
     * @var float
     */
    private $balance;

    /**
     * Response constructor.
     */
    public function __construct()
    {
        $this->status_code = 0;
        $this->sms = [];
    }

    /**
     * @return \AppBundle\Sms\SmsRu\Response\Sms[]
     */
    public function getFailedSms()
    {
        $failed = [];

        foreach ($this->sms as $recipient => $sms) {
            if (!$sms->isSuccessfull()) {
                $failed[$recipient] = $sms;
            }
        }

        return $failed;
    }

    /**
     * @return bool
     */
    public function isSuccessfull()
    {
        return 'OK' === $this->status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * @return string
     */
    public function getStatusText()
    {
        return $this->status_text;
    }

    /**
     * @return \AppBundle\Sms\SmsRu\Response\Sms[]
     */
    public function getSms()
    {
        return $this->sms;
    }

    /**
     * @return float
     */
    public function getBalance()
    {
        return $this->balance;
    }
}
