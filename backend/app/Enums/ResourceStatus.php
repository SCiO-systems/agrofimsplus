<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * The resource status.
 */
final class ResourceStatus extends Enum
{
    const DRAFT = 'draft';
    const UNDER_PREPARATION = 'under_preparation';
    const UNDER_REVIEW = 'under_review';
    const APPROVED = 'approved';
    const PUBLISHED = 'published';
}
