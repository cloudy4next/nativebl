<?php declare(strict_types=1);

namespace App\Services\Settings;

use App\Contracts\Services\Settings\PermissionServiceInterface;
use Session;
use NativeBL\Repository\AbstractNativeRepository;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;
use App\Traits\APITrait;
use App\Traits\HelperTrait;
use Auth;
use Illuminate\Support\Collection;

final class PermissionService extends AbstractNativeRepository implements PermissionServiceInterface, CrudGridLoaderInterface
{
    use APITrait, HelperTrait;
    public function getModelFqcn(): string
    {
        return '';
    }

    public function getGridData(array $filters=[]): iterable
    {

        return $this->getAllPermission();
    }


    public function applyFilterData(Collection $data, array $filters) : Collection
    {
        foreach($filters as $field=>$value){
            $filtered = $data->where($field,$value);
            $data = $filtered;
        }
        return $data;
    }

    public function getAllPermission()
    {
        $path = '/Api/AppUserManagement/AppPermissions?id=' . (int) $this->SessionCheck('applicationID');
        $response = $this->apiResponse('GET', null, null, $path);
        return $this->featureSet($response->data);
    }
    public function getApplication(): array
    {
        return $this->getApplications();
    }

    public function savePermission($request)
    {
        $json = [
            "title" => $request->get('title'),
            "shortDescription" => $request->get('shortDescription'),
            "applicationID" => $request->get('applicationID'),
            "createdBy" => Auth::user()->id,
        ];
        $path = '/Api/AppUserManagement/SaveAppPermission';
        $this->apiResponse('POST', null, $json, $path);

        return true;
    }

    public function getSinglePermission(int $id)
    {
        $path = '/Api/AppUserManagement/AppPermission?id=' . $id;
        return $this->apiResponse('GET', null, null, $path);
    }



    public function updatePermission($request)
    {
        $json = [
            "permissionID" => $request->get('id'),
            "title" => $request->get('title'),
            "shortDescription" => $request->get('shortDescription'),
            "applicationID" => $request->get('applicationID'),
            "menus" => $request->get('menus'),
            "updatedBy" => Auth::user()->id,
        ];
        $path = '/Api/AppUserManagement/SaveAppPermission';
        $this->apiResponse('POST', null, $json, $path);

        return true;

    }

    public function deletePermission(int $id)
    {
        $path = '/Api/AppUserManagement/DeleteApPermission?id=' . $id . '&userID=' . Auth::user()->id;
        $this->apiResponse('GET', null, null, $path);

    }
}
