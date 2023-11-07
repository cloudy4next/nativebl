<?php

declare(strict_types=1);


namespace App\Contracts\Services\DND;

interface BaseDNDServiceInterface
{
    function SetAsOnOff(array $dndData);
    function getChannels(): array;
}
