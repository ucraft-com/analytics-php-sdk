<?php

declare(strict_types = 1);

namespace Uc\Analytics\Enums;

enum MessageType: string
{
    case TRACK = 'track';
    case IDENTIFY = 'identify';
}
