<?php

namespace App\Traits;

use App\Exceptions\NotFoundException;
use Session;
use Auth;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * 🚧 WARNING: Don't modify unless so.
 * 📚 HelperTrait is used for global Management only eg: Login, Role, Permission, User.
 * @author cloudy4next <jahangir7200@live.com>
 */
trait HelperTrait
{
    /**
     * If Session value exits it return value else logout the user.
     */
    private function SessionCheck(string $value)
    {
        if (Session::has($value) == true) {
            return Session::get($value);
        }
        Auth::logout();
    }

    private function SessionValueSet(string $name, string|int|array $value)
    {
        if (Session::has($name)) {
            return Session::put($name, $value);
        } else {
            throw new NotFoundException('No Session Data found');
        }
    }

    /**
     * @return array of role
     */
    public function roles()
    {
        return $this->featureSet($this->SessionCheck('role'));
    }

    /**
     * @return mixed user permission from session
     */
    public function userPermission(): array
    {
        $permissions = $this->featureSet($this->SessionCheck('permission'));

        $newPermmissionArr = array();
        foreach ($permissions as $item) {
            $newPermmissionArr[] = $item['name'];
        }
        return $newPermmissionArr;
    }

    /**
     * @param array $item Array
     * @return array stdClass to array
     */
    public function featureSet($item): array
    {
        $result = array();
        foreach ($item as $value) {
            $result[] = (array) $value;
        }
        return $result;
    }
    private function CreateUser(string $id, string $name, string $email): void
    {
        $user = new User();
        $user->id = $id;
        $user->user_name = $name;
        $user->email = $email;
        $user->save();
    }

    /**
     * @return array array set from parse data eg: role, permission, menu in User Class & etc.
     */
    public function getSingleValueFromArr(array $data, string $name): array
    {
        $itemArray = array();
        foreach ($data[$name] as $item) {
            array_push($itemArray, $item->id);
        }
        return $itemArray;
    }

    /**
     * @return array of appplication having id-name pair
     */
    public function getMultiValueFromArr(array $data): array
    {
        $itemArr = array();
        foreach ($data as $item) {
            $itemArr[$item->applicationID] = $item->applicationName;
        }
        return $itemArr;
    }

    /**
     * @return array return menu parent child relation
     */
    public function parentChildMenuItem(array $menu)
    {
        $parentChild = array();
        $newParentChildPair = array();
        foreach ($menu as $item) {
            if ($item['parentID'] == null) {
                array_push($parentChild, $item);
            }
        }

        foreach ($parentChild as $parent) {
            $childIDs = array();
            foreach ($menu as $value) {
                if ($parent['id'] == $value['parentID']) {
                    array_push($childIDs, $value);
                }
                $newParentChildPair[$parent['name']] = $childIDs;
            }
        }
        return $newParentChildPair;
    }


    /**
     * @param array $haystack
     * @param mixed $identifier could be null or key value to get eg: [1=>[],'sting'=>[]]
     * @return array return Set of feature
     */
    private function GetFeatSets(array $hayStack, mixed $identifier): array
    {
        $result = array();
        foreach ($hayStack as $value) {
            if ($identifier == null) {
                $result[] = $value;
            } else {
                $result[$value[$identifier]] = $value;
            }
        }
        return $result;
    }

    public function menuKeypair(array $data): array
    {
        $newParentID = array();
        foreach ($this->getAllMenu() as $menu) {
            $newParentID[$menu['id']] = $menu['title'];
        }
        return $newParentID;
    }

    public function UserDataProcessing($userObj, string $access, string $refresh): array
    {
        $userId = $userObj['data']['userID'];
        $email = $userObj['data']['emailAddress'];
        $userName = $userObj['data']['userName'];
        $menus = $this->parentChildMenuItem($this->GetFeatSets($userObj['data']['menus'], 'name'));
        $roles = $this->GetFeatSets($userObj['data']['roles'], 'name');
        $permissions = $this->GetFeatSets($userObj['data']['permissions'], null);
        $applicatopnID = $userObj['data']['applicationID'];
        $isMustPasswordChange = $userObj['data']['isMustChangePassword'];

        return [$userId, $userName, $email, $menus, $roles, $permissions, $access, $refresh, $applicatopnID, $isMustPasswordChange];
    }

    public function getApplications()
    {
        $applicationID = $this->SessionCheck('applicationID');
        $path = '/Api/AppUserManagement/Aplications';
        $response = $this->apiResponse('GET', null, null, $path);
        $applicationArray = $this->getMultiValueFromArr($response->data);
        $singleApplication[$applicationID] = $applicationArray[$applicationID];

        // here key 1 is the master application. need to refactor if needed
        return ($applicationID == 1) ? $applicationArray : $singleApplication;

    }
    public function generateUUID(): string
    {
        return (string) Str::orderedUuid();
    }



}
