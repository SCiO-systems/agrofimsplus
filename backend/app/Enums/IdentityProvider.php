<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * The available identity providers.
 */
final class IdentityProvider extends Enum
{
    const SCRIBE = 'scribe';
    const GLOBUS = 'globus';
    const ORCID = 'orcid';
}
