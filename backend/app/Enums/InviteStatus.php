<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * The invite status.
 */
final class InviteStatus extends Enum
{
    public const Pending = 'pending';
    public const Accepted = 'accepted';
    public const Rejected = 'rejected';
}
