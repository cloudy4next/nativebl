<?php declare(strict_types=1);


namespace App\Contracts\DBSSESim;


interface ESimRepositoryInterface
{
    function getESinfo(int|null $msisdn);
}
