<?php

declare(strict_types = 1);

namespace Uc\Analytics;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Uc\Analytics\Interfaces\AnalyticsTrackerInterface;

/**
 * @package Uc\Analytics
 */
class Analytics implements AnalyticsTrackerInterface
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected Client $httpClient;

    /**
     * @var string
     */
    protected string $apiUrl;

    /**
     * @param \GuzzleHttp\Client $httpClient
     * @param string             $apiUrl
     */
    public function __construct(Client $httpClient, string $apiUrl)
    {
        $this->httpClient = $httpClient;
        $this->apiUrl = $apiUrl;
    }

    /**
     * @param \Uc\Analytics\Message $message
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function track(Message $message) : void
    {
        $message = $this->enhanceMessage($message);

        $this->sendRequest('track', $message);
    }

    /**
     * @param \Uc\Analytics\Message $message
     *
     * @return \Uc\Analytics\Message
     */
    protected function enhanceMessage(Message $message) : Message
    {
        $messageBody = $message->getBody();

        $messageBody['context'] = array_merge(
            $messageBody['context'] ?? [],
            $this->getDefaultContext()
        );

        $messageBody['originalTimestamp'] = $timestamp = date('c');
        $messageBody['sentAt'] = $timestamp;

        $message->setBody($messageBody);

        return $message;
    }

    /**
     * @return \string[][]
     */
    protected function getDefaultContext() : array
    {
        return [
            'library' => [
                'name'    => 'analytics-php-sdk',
                'version' => '1.0.0'
            ],
        ];
    }

    /**
     * @param string                $url
     * @param \Uc\Analytics\Message $message
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendRequest(string $url, Message $message) : ResponseInterface
    {
        return $this->httpClient->post(sprintf('%s/%s', $this->apiUrl, $url), [
            'json' => $message->getBody()
        ]);
    }

}
