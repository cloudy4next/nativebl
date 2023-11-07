<?php

namespace App\Traits;

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
        return Session::get($value);
    }

    public function checkifExitsApplication(int $id): bool
    {
        return ($this->SessionCheck('applicationID') == $id) ? true : false;
    }

    /**
     * @return array of role
     */
    public function roles()
    {
        return $this->featureSet($this->SessionCheck('role'));
    }
    //TO--do
    public function setOnlyArray($set)
    {
        $permissions = $this->featureSet($set);

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
    public function parentChildMenuItem(array $menu, $parentId = null)
    {
        $result = [];
        foreach ($menu as $item) {
            if ($item['parentID'] == $parentId) {
                $children = $this->parentChildMenuItem($menu, $item['id']);

                $item['children'] = !empty($children) ? $children : [];

                $result[$item['name']] = $item;
            }
        }
        return $result;
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
        // dd($this->GetFeatSets($userObj['data']['menus'], 'name'));
        $userId = $userObj['data']['userID'];
        $email = $userObj['data']['emailAddress'];
        $userName = $userObj['data']['userName'];
        $menus = $this->parentChildMenuItem($this->GetFeatSets($userObj['data']['menus'], 'name'));
        $roles = $this->GetFeatSets($userObj['data']['roles'], 'name');
        $applicatopnID = $userObj['data']['applicationID'];
        $isMustPasswordChange = $userObj['data']['isMustChangePassword'];
        $profilePicID = $userObj['data']['profilePicID'];

        return [$userId, $userName, $email, $menus, $roles, $profilePicID, $access, $refresh, $applicatopnID, $isMustPasswordChange];
    }

    public function getApplications()
    {
        $applicationID = $this->SessionCheck('applicationID');
        $path = '/Api/AppUserManagement/Aplications';
        $response = $this->apiResponse('GET', null, null, $path);
        $applicationArray = $this->getMultiValueFromArr($response->data);
        $singleApplication[$applicationID] = $applicationArray[$applicationID];
        return ($applicationID == config('nativebl.base.core_application_id')) ? $applicationArray : $singleApplication;

    }
    public function generateUUID(): string
    {
        return (string) Str::orderedUuid();
    }

    public function getApplictionId(): int
    {
        return $this->SessionCheck('applicationID');
    }

    public function getTotalListItem(array|object $userInformation, array $listOfItem): array
    {
        $excludeItems = [];
        foreach ($userInformation as $userItem) {
            if ($userItem->applicationID != Session::get('applicationID')) {
                $excludeItems[] = (array) $userItem;
            }
        }
        $result = array_merge($excludeItems, $listOfItem);
        $result = array_values(array_intersect_key($result, array_unique(array_column($result, 'id'))));
        return $result;

    }
    public function getResetHtmlContent($resetToken)
    {

        $htmlContent = \View::make('auth.reset-view', compact('resetToken'))->render();

        return json_encode(['html_content' => $htmlContent]);

    }

}
