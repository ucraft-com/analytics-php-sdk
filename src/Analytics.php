<?php

declare(strict_types = 1);

namespace Uc\Analytics;

use Carbon\Carbon;
use Exception;
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
     * @var array
     */
    protected array $options;

    /**
     * @param \GuzzleHttp\Client $httpClient
     * @param string             $apiUrl
     * @param array              $options
     */
    public function __construct(Client $httpClient, string $apiUrl, array $options)
    {
        $this->httpClient = $httpClient;
        $this->apiUrl = $apiUrl;
        $this->options = $options;
    }

    /**
     * @param string                $event
     * @param \Uc\Analytics\Message $message
     *
     * @return void
     */
    public function track(string $event, Message $message) : void
    {
        $message = $this->enhanceMessage($event, $message);

        $this->sendRequest('track', $message);
    }

    /**
     * @param string                $event
     * @param \Uc\Analytics\Message $message
     *
     * @return \Uc\Analytics\Message
     */
    protected function enhanceMessage(string $event, Message $message) : Message
    {
        $messageBody = $message->getBody();
        $messageBody['event'] = $event;
        $messageBody['context'] = array_merge(
            $messageBody['context'] ?? [],
            $this->getDefaultContext()
        );

        $date = Carbon::now();

        if (empty($messageBody['originalTimestamp'])) {
            $messageBody['originalTimestamp'] = $date->toISOString();
        }

        $messageBody['sentAt'] = $date->toISOString();

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
                'version' => $this->options['library_version'],
            ],
        ];
    }

    /**
     * @param string                $url
     * @param \Uc\Analytics\Message $message
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendRequest(string $url, Message $message) : void
    {
        if (!empty($this->options['analytics_tracking_enabled'])) {
            $this->httpClient->post(sprintf('%s/%s', $this->apiUrl, $url), [
                'json' => $message->getBody()
            ]);
        }
    }

}
