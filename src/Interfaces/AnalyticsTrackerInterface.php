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
     * @param \Uc\Analytics\Message $message
     *
     * @return void
     */
    public function track(Message $message) : void;
}
