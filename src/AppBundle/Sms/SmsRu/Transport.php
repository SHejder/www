<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Sms\SmsRu;

use AppBundle\Sms\SmsException;
use AppBundle\Sms\SmsRu\Response\Response;
use AppBundle\Sms\SmsTransportInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Psr\Log\LoggerInterface;

/**
 * sms.ru SMS transport
 */
class Transport implements SmsTransportInterface
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    private $httpClient;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $apiId;

    /**
     * @var bool
     */
    private $test;

    /**
     * @var \JsonMapper
     */
    private $mapper;

    /**
     * @param \GuzzleHttp\ClientInterface $httpClient HTTP client
     * @param \Psr\Log\LoggerInterface    $logger     Logger
     * @param string                      $apiId      API ID
     * @param bool                        $test       Is test mode
     */
    public function __construct(ClientInterface $httpClient, LoggerInterface $logger, $apiId, $test)
    {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
        $this->apiId = $apiId;
        $this->test = $test;

        $this->mapper = new \JsonMapper();
        $this->mapper->bExceptionOnMissingData = $this->mapper->bIgnoreVisibility = $this->mapper->bStrictObjectTypeChecking = true;
        $this->mapper->setLogger($logger);
    }

    /**
     * {@inheritdoc}
     */
    public function send($to, $message, array $options = [])
    {
        if (empty($to)) {
            return null;
        }

        $to = array_unique((array) $to);

        $url = $this->buildUrl($message, $options);

        try {
            $httpResponse = $this->httpClient->request('post', $url, [
                RequestOptions::FORM_PARAMS => [
                    'to' => implode(',', $to),
                ],
            ]);
        } catch (GuzzleException $ex) {
            throw $this->createException(
                sprintf('Unable to get response from URL "%s": "%s".', $url, $ex->getMessage()),
                $ex->getCode(),
                $ex
            );
        }

        $json = $httpResponse->getBody()->getContents();

        $data = json_decode($json);

        if (null === $data) {
            throw $this->createException(
                sprintf('Unable to decode response from URL "%s" as JSON: "%s" (response: "%s").', $url, json_last_error_msg(), $json)
            );
        }
        try {
            /** @var \AppBundle\Sms\SmsRu\Response\Response $response */
            $response = $this->mapper->map($data, new Response());
        } catch (\JsonMapper_Exception $ex) {
            throw $this->createException(
                sprintf('Unable to create response object: "%s" (response: "%s").', $ex->getMessage(), $json)
            );
        }
        if (!$response->isSuccessfull()) {
            throw $this->createException($response->getStatusText(), $response->getStatusCode());
        }

        $errors = [];

        foreach ($response->getFailedSms() as $recipient => $sms) {
            $errors[] = sprintf('%s: %s', $recipient, $sms->getStatusText());
        }
        if (count($errors) === count($to)) {
            throw $this->createException(sprintf('All recipients failed: "%s".', implode('; ', $errors)));
        }
        foreach ($errors as $message) {
            $this->logError($message);
        }

        return $response;
    }

    /**
     * @param string $message Message
     * @param array  $options Options
     *
     * @return string
     */
    private function buildUrl($message, array $options)
    {
        $params = array_merge([
            'api_id' => $this->apiId,
            'msg'    => $message,
            'json'   => true,
            'test'   => $this->test,
        ], $options);

        return '/sms/send?'.http_build_query($params);
    }

    /**
     * @param string     $message  Message
     * @param int        $code     Code
     * @param \Throwable $previous Previous exception
     *
     * @return \AppBundle\Sms\SmsException
     */
    private function createException($message, $code = 0, \Throwable $previous = null)
    {
        $this->logError($message);

        return new SmsException($message, $code, $previous);
    }

    /**
     * @param string $message Message
     */
    private function logError($message)
    {
        $this->logger->error(implode(' ', [get_class($this), $message]));
    }
}
