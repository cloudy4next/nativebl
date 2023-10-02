<?php declare(strict_types=1);

namespace App\Services\Settings;

use App\Contracts\Services\Settings\MenuServiceInterface;
use NativeBL\Repository\AbstractNativeRepository;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;
use App\Traits\APITrait;
use App\Traits\HelperTrait;
use Auth;

use Illuminate\Support\Collection;

final class MenuService extends AbstractNativeRepository implements MenuServiceInterface, CrudGridLoaderInterface
{
    use APITrait, HelperTrait;

    public function getGridData(array $filters=[]): iterable
    {
        return $this->getAllMenu();

    }

    public function getAllMenu(): mixed
    {
        $path = '/Api/AppUserManagement/AppMenus?id=' . (int) $this->SessionCheck('applicationID');
        $response = $this->apiResponse('GET', null, null, $path);
        return $this->featureSet($response->data);
    }

    public function applyFilterData(Collection $data, array $filters): Collection
    {
        foreach ($filters as $field => $value) {
            if ($value !== null) {
                $data = $data->filter(function ($item) use ($field, $value) {
                    return stripos($item[$field], $value) !== false;
                });
            }
        }
        return $data;
    }

    /**
     * @return null|array menu item by userid.
     */
    public function getMenubyID(): mixed
    {
        $path = '/Api/AppUserManagement/AppUser?id=' . Auth::user()->id;

        $response = $this->apiResponse('GET', null, null, $path);

        return $this->featureSet($response->data->menus);
    }

    public function getApplication(): array
    {
        return $this->getApplications();
    }

    public function singleMenu(int $id): \stdClass
    {
        $path = '/Api/AppUserManagement/AppMenu?id=' . $id;

        $response = $this->apiResponse('GET', null, null, $path);

        return $response;
    }

    public function getModelFqcn(): string
    {
        return '';
    }

    /**
     * @param \request $request object
     * @return mixed could be true or 404
     */
    public function saveMenu($request)
    {
        $json = [
            "applicationID" => $request->get('applicationID'),
            "title" => $request->get('title'),
            "iconName" => $request->get('iconName'),
            "displayOrder" => $request->get('displayOrder'),
            "target" => $request->get('target'),
        ];

        if ($request->get('id') != null) {
            $json['menuID'] = $request->get('id');
            $json['updatedBy'] = Auth::user()->id;
        } else {
            $json['createdBy'] = Auth::user()->id;
        }
        if ($request->get('parentID') != -1) {
            $json['parentID'] = $request->get('parentID');
        }


        $path = '/Api/AppUserManagement/SaveAppMenu';
        $this->apiResponse('POST', null, $json, $path);
        return true;
    }

    public function deleteMenu(string $id)
    {
        $path = '/Api/AppUserManagement/DeleteAppMenu?id=' . $id . '&userID=' . Auth::user()->id;
        $this->apiResponse('GET', null, null, $path);
        return true;
    }

    public function keyPairParentID()
    {
        return $this->menuKeypair($this->getAllMenu());
    }

}
