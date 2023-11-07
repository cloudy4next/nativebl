<?php declare(strict_types=1);


namespace App\Services\DBSSESim;

use App\Contracts\DBSSESim\ESimRepositoryInterface;
use App\Contracts\Services\DBSSESim\ESimServiceInterface;


class ESimService implements ESimServiceInterface
{
    public function __construct(private ESimRepositoryInterface $esimRepository)
    {
        $this->esimRepository = $esimRepository;
    }


    public function getESimCollection(int|null $msisdn): array
    {
        return $this->esimRepository->getESinfo($msisdn);
    }

}
