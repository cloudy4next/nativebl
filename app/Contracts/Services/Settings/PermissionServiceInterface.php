<?php  declare(strict_types=1);


namespace App\Contracts\Services\Settings;

interface PermissionServiceInterface
{

    function getAllPermission();
    function savePermission($request);
    function updatePermission($request);
    function deletePermission(int $id);
    function getSinglePermission(int $id);
    function getApplication(): array ;


}

