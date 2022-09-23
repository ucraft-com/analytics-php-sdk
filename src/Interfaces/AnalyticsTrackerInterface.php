<?php

declare(strict_types = 1);

namespace Uc\Analytics\Interfaces;

use Uc\Analytics\Message;

/**
 * @package Uc\Analytics
 */
interface AnalyticsTrackerInterface
{
    /**
     * @param string                $event
     * @param \Uc\Analytics\Message $message
     *
     * @return void
     */
    public function track(string $event, Message $message) : void;
}
