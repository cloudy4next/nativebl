<?php declare(strict_types=1);

namespace App\Contracts\Services\DBSSESim;

interface ESimServiceInterface
{
    function getESimCollection(int|null $msisdn): array;
}
