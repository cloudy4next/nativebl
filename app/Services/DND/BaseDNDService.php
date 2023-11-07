<?php

declare(strict_types=1);


namespace App\Services\DND;

use App\Contracts\DND\BaseDNDRepositoryInterface;
use App\Contracts\Services\DND\BaseDNDServiceInterface;

class BaseDNDService implements BaseDNDServiceInterface
{

    public function __construct(private BaseDNDRepositoryInterface $baseDNDRepositoryInterface)
    {
        $this->baseDNDRepositoryInterface = $baseDNDRepositoryInterface;
    }

    public function SetAsOnOff(array $dndData)
    {
        return $this->baseDNDRepositoryInterface->sendDNDData($dndData);
    }

    public function getChannels(): array
    {
        return $this->baseDNDRepositoryInterface->channels();
    }
}
