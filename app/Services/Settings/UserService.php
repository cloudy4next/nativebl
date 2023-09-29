<?php declare(strict_types=1);

namespace App\Services\Settings;

use App\Contracts\Services\Settings\UserServiceInterface;
use App\Exceptions\NotFoundException;
use App\Models\User;
use NativeBL\Repository\AbstractNativeRepository;
use App\Traits\APITrait;
use App\Traits\HelperTrait;
use Auth;
use Illuminate\Support\Collection;

final class UserService extends AbstractNativeRepository implements UserServiceInterface
{
    use APITrait, HelperTrait;

    public function getModelFqcn(): string
    {
        return '';
    }
    
    public function getGridData(array $filters=[]): iterable
    {
        return $this->featureSet($this->getAllUser());

    }

    public function applyFilterData(Collection $data, array $filters) : Collection
    {
        foreach($filters as $field=>$value){
            $filtered = $data->where($field,$value);
            $data = $filtered;
        }
        return $data;
    }

    /**
     * @return array all user's
     */
    public function getAllUser()
    {
        $path = '/Api/AppUserManagement/AppUsers?id=' . (int) $this->SessionCheck('applicationID');
        $response = $this->apiResponse('GET', null, null, $path);
        return $response->data;
    }
    /**
     * @param string $id colud be auth user id or specific id
     * @return $response with user information
     */
    public function getSingleUser(string $id)
    {
        $path = '/Api/AppUserManagement/AppUser?id=' . $id;
        $response = $this->apiResponse('GET', null, null, $path);
        return $response;
    }
    public function userApplicationID()
    {
        return $this->SessionCheck('applicationID');
    }

    public function getApplication()
    {
        return $this->getApplications();
    }

    public function getAllUserIDNameArr(): array
    {
        $userListArr = array();
        foreach ($this->getAllUser() as $user) {

            $userListArr[$user->userID] = $user->userName . '(' . $user->emailAddress . ')';
        }
        return $userListArr;
    }
    public function getApplicationName(int $id): mixed
    {
        $applicationIDs = $this->getApplications();
        foreach ($applicationIDs as $key => $value) {
            if ($key == $id) {
                return $value;
            }
        }
        throw new NotFoundException('No Application Found');
    }
    public function getRoles(): array
    {
        return $this->roles();
    }
    public function dataParseFromArr(array $item, string $name)
    {
        return $this->getSingleValueFromArr($item, $name);
    }

    /**
     * @param \request $request respective view
     * @return mixed could be true or 404
     */
    public function saveUser($request): bool
    {
        $randomUserID = $this->generateUUID();
        $json = [
            "userID" => $randomUserID,
            "userName" => $request->get('userName'),
            "fullName" => $request->get('fullName'),
            "password" => $request->get('password'),
            "emailAddress" => $request->get('emailAddress'),
            "mobileNumber" => $request->get('mobileNumber'),
            "createdBy" => Auth::user()->id,
            "grantType" => $request->get('GrantType'),
            "roleIDs" => $request->get('roles'),
            "defaultApplicationID" => (int) $request->get('applicationID'),
            "permissionIDs" => $request->get('permissions'),
        ];

        if ($this->checkIfToffee()) {
            $json["grantType"] = $request->get('GrantType');
        }
        $path = '/Api/AppUserManagement/SaveUser';

        $test = $this->apiResponse('POST', null, $json, $path);
        if ($test->errorSummary == "") {
            $this->createUser($randomUserID, $request->get('userName'), $request->get('emailAddress'));
        }
        return true;
    }

    public function updateUser($request): bool
    {

        $json = [
            "userID" => $request->get('id'),
            "userName" => $request->get('userName'),
            "fullName" => $request->get('fullName'),
            "password" => $request->get('password'),
            "emailAddress" => $request->emailAddress,
            "mobileNumber" => $request->get('mobileNumber'),
            "roleIDs" => $request->get('roles'),
            "updatedBy" => Auth::user()->id,
            "permissionIDs" => $request->get('permissions'),

        ];

        $path = '/Api/AppUserManagement/SaveUser';

        $this->apiResponse('POST', null, $json, $path);
        return true;
    }
    public function deleteUser(string $id)
    {
        $path = '/Api/AppUserManagement/DeleteAppUser?id=' . $id . '&userID=' . Auth::user()->id;
        $this->apiResponse('GET', null, null, $path);
        return true;
    }


    public function updateUserPassword($request): bool
    {
        $json = [
            "userID" => Auth::user()->id,
            "password" => $request->get('password'),
        ];
        $path = '/Api/AppUserManagement/UserPasswordChange';

        $this->apiResponse('POST', null, $json, $path);
        return true;
    }
    public function userOldPasswordCheck(string $oldPassword): bool|\Exception
    {
        $userPassword = $this->getSingleUser(Auth::user()->id);
        return ($userPassword->data->password == $oldPassword) ? true : throw new NotFoundException('Passowrd is not same as old password');
    }

    public function checkIfToffee(): bool
    {
        return ($this->SessionCheck('applicationID') == 4) ? true : false;
    }
}
