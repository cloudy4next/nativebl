<?php declare(strict_types=1);

namespace App\Contracts\TigerWeb;


interface CustomerRepositoryInterface
{
    function getAllCustomer();
    function findCustomerByMsisdn(string $msisdn);
}
