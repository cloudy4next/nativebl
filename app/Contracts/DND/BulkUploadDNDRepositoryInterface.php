<?php

declare(strict_types=1);

namespace App\Contracts\DND;

use DateTime;

interface BulkUploadDNDRepositoryInterface
{
    function getExportData();
    function saveUplaodedData($file,$shecduleDate);
}
