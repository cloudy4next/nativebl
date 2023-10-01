<?php declare(strict_types=1);

namespace App\Services\Settings;

use App\Contracts\Services\Settings\RoleServiceInterface;
use NativeBL\Repository\AbstractNativeRepository;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;
use App\Traits\APITrait;
use App\Traits\HelperTrait;
use Auth;
use Illuminate\Support\Collection;

final class RoleService extends AbstractNativeRepository implements RoleServiceInterface, CrudGridLoaderInterface
{
    use APITrait, HelperTrait;
    public function getModelFqcn(): string
    {
        return '';
    }


    public function getGridData(array $filters=[]): iterable
    {
        $role = $this->featureSet($this->getAllRole());
        return $role;

    }

    public function applyFilterData(Collection $data, array $filters): Collection
    {
        return $data->filter(function ($item) use ($filters) {
            foreach ($filters as $field => $value) {
                if ($value !== null) {
                    $found = stripos($item[$field], $value) !== false;

                    if ($found) {
                        return $found;
                    }
                }
            }
            return false;
        });
    }

    public function getAllRole()
    {
        $path = '/Api/AppUserManagement/AppRoles?id=' . (int) $this->SessionCheck('applicationID');
        $response = $this->apiResponse('GET', null, null, $path);

        return $response->data;
    }
    public function getApplication(): array
    {
        return $this->getApplications();
    }

    /**
     * @param \request $request respective from data
     * @return mixed could be true or throws exception
     */
    public function saveRole($request): bool
    {
        $json = [
            "title" => $request->get('title'),
            "shortDescription" => $request->get('shortDescription'),
            "applicationID" => $request->get('applicationID'),
            "menus" => $request->get('menus'),
            "createdBy" => Auth::user()->id,

        ];
        $path = '/Api/AppUserManagement/AppRole';
        $this->apiResponse('POST', null, $json, $path);
        return true;
    }
    public function getSingleRole(int $id)
    {
        $path = '/Api/AppUserManagement/AppRole?id=' . $id;
        $response = $this->apiResponse('GET', null, null, $path);
        return $response;

    }

    public function updateRole($request)
    {
        $json = [
            "roleID" => $request->get('id'),
            "title" => $request->get('title'),
            "shortDescription" => $request->get('shortDescription'),
            "applicationID" => $request->get('applicationID'),
            "menus" => $request->get('menus'),
            "updatedBy" => Auth::user()->id,
        ];
        $path = '/Api/AppUserManagement/AppRole';
        $this->apiResponse('POST', null, $json, $path);
        return true;

    }
    public function deleteRole(int $id)
    {
        $path = '/Api/AppUserManagement/DeleteAppRole?id=' . $id . '&userID=' . Auth::user()->id;
        $this->apiResponse('GET', null, null, $path);

    }

}
