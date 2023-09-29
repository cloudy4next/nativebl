<?php declare(strict_types=1);


namespace App\Contracts\Services\Settings;

interface UserServiceInterface
{
    function getSingleUser(string $id);
    function saveUser($request);
    function updateUser($request);
    function updateUserPassword($request);
    function deleteUser(string $id);
    function getRoles();
    function getApplication();
    function dataParseFromArr(array $data, string $name);
    function getApplicationName(int $id);
    function userApplicationID();
    function userOldPasswordCheck(string $oldPassword): bool|\Exception;
    function getAllUserIDNameArr(): array;
    function checkIfToffee(): bool;
}
