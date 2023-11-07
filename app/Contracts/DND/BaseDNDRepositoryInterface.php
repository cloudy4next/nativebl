<?php

declare(strict_types=1);

namespace App\Contracts\DND;

use DateTime;

interface BaseDNDRepositoryInterface
{
    function sendDNDData(array $dndData);
    function channels(): array;
}
