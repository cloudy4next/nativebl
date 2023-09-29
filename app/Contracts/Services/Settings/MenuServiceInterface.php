<?php declare(strict_types=1);


namespace App\Contracts\Services\Settings;

interface MenuServiceInterface
{
    function getAllMenu();
    function saveMenu($request);
    function deleteMenu(string $id);
    function singleMenu(int $id): \stdClass ;
    function keyPairParentID();
    function getApplication(): array ;

}
