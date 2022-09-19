<?php

declare(strict_types = 1);

namespace Uc\Analytics;

use GuzzleHttp\Client;
use Uc\Analytics\Enums\MessageType;
use Uc\Analytics\Interfaces\AnalyticsTrackerInterface;

class Analytics implements AnalyticsTrackerInterface
{
    protected Client $httpClient;

    protected string $apiUrl;

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

        $response = $this->sendRequest('track', $message);

        dd($response);
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

        $messageBody['originalTimestamp'] = $timestamp = date('c');;
        $messageBody['sentAt'] = $timestamp;

        $message->setBody($messageBody);

        return $message;
    }

    protected function getDefaultContext() : array
    {
        return [
            'library' => [
                'name'    => 'analytics-php-sdk',
                'version' => '1.0.0'
            ],
        ];
    }

    public function sendRequest(string $url, Message $message)
    {
        return $this->httpClient->post(sprintf('%s/%s', $this->apiUrl, $url), [
            'json' => $message->getBody()
        ]);
    }

}
