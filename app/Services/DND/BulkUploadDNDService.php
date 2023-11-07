<?php

declare(strict_types=1);


namespace App\Services\DND;

use App\Contracts\DND\BulkUploadDNDRepositoryInterface;
use App\Contracts\Services\DND\BulkUploadDNDServiceInterface;

class BulkUploadDNDService implements BulkUploadDNDServiceInterface
{

    public function __construct(private BulkUploadDNDRepositoryInterface $bulkUploadDNDRepositoryInterface)
    {
        $this->bulkUploadDNDRepositoryInterface = $bulkUploadDNDRepositoryInterface;
    }

}
