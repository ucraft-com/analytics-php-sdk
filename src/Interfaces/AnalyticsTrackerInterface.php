<?php

declare(strict_types = 1);

namespace Uc\Analytics\Interfaces;

use Uc\Analytics\Message;

interface AnalyticsTrackerInterface
{
    public function track(Message $message) : void;
}
