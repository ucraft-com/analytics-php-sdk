<?php

declare(strict_types = 1);

namespace Uc\Analytics;

/**
 * @package Uc\Analytics
 */
class Message
{
    /**
     * @var array
     */
    protected array $body;

    /**
     * @param array $body
     */
    public function __construct(array $body = [])
    {
        $this->body = $body;
    }

    /**
     * @return array
     */
    public function getBody() : array
    {
        return $this->body;
    }

    /**
     * @param array $body
     *
     * @return void
     */
    public function setBody(array $body) : void
    {
        $this->body = $body;
    }

}
