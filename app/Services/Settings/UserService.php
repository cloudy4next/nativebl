<?php

declare(strict_types=1);

namespace App\Services\Settings;

use App\Contracts\Services\Settings\UserServiceInterface;
use App\Exceptions\NotFoundException;
use NativeBL\Repository\AbstractNativeRepository;
use App\Traits\APITrait;
use App\Traits\HelperTrait;
use App\Traits\AttachmentUploadTrait;
use Auth;
use Illuminate\Support\Collection;
use Str;

final class UserService extends AbstractNativeRepository implements UserServiceInterface
{
    use APITrait, HelperTrait, AttachmentUploadTrait;

    public function getModelFqcn(): string
    {
        return '';
    }

    public function getGridData(array $filters = []): ?iterable
    {
        return $this->featureSet($this->getAllUser());
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
        $path = '/Api/AppUserManagement/AppUser?id=' . $id . '&email=null';
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
        $resetToken = Str::random(75);
        $randomUserID = $this->generateUUID();
        $imageID = $request->file('image') == null ? '' : $this->upload($request->file('image'));
        $isActive = ($request->get('GrantType') == 'bl_active_directory') ? 0 : 1;
        $password = ($request->get('GrantType') == 'bl_active_directory' && $request->get('GrantType') != null) ? null : $request->get('password');
        $json = [
            "userID" => $randomUserID,
            "userName" => $request->get('userName'),
            "fullName" => $request->get('fullName'),
            "password" => $password,
            "emailAddress" => $request->get('emailAddress'),
            "mobileNumber" => $request->get('mobileNumber'),
            "createdBy" => Auth::user()->id,
            "grantType" => $request->get('GrantType'),
            "roleIDs" => $request->get('roles'),
            "defaultApplicationID" => (int) $request->get('applicationID'),
            "permissionIDs" => $request->get('permissions'),
            "profilePicID" => $imageID,
            "resetToken" => $resetToken,
            "PasswordResetContent" => $this->getResetHtmlContent($resetToken),
            "isMustChangePassword" => $isActive,
        ];

        if ($this->getApplictionId() == config('nativebl.base.toffee_analytics_application_id')) {
            $json["grantType"] = $request->get('GrantType');
        }
        $response = $this->apiResponse('POST', null, $json, '/Api/AppUserManagement/SaveUser');
        if ($response->errorSummary == "") {
            $this->createUser($randomUserID, $request->get('userName'), $request->get('emailAddress'));
        }
        // dd($response);

        //---------------------------------------------------------------------------------//
        /*
            | only for testing perpose 🙃
        */


        // if ($request->get('GrantType') == "email_password") {
        //     /*
        //       | Send User Password Reset Email;
        //     */
        //     $mailJson = [
        //         "fromEmail" => env('APP_NAME'),
        //         "toEmail" => $request->get('emailAddress'),
        //         "applicationID" => $this->SessionCheck("applicationID"),
        //         "Subject" => 'Password Reset',
        //         "Body" => $this->getResetHtmlContent($resetToken),
        //         "createdBy" => Auth::user()->id,
        //         "refType" => "password",
        //     ];
        //     $mailpath = '/Api/AppUserManagement/SendEmail';
        //     $this->apiResponse('POST', null, $mailJson, $mailpath);
        //     try {
        //         $this->apiResponse('POST', null, $mailJson, $mailpath);
        //     } catch (\Exception $e) {
        //         dd($e);
        //         throw new NotFoundException("Invalid e-mail server configuration found for the given application.");
        //     }
        // }

        //---------------------------------------------------------------------------------//

        return true;
    }

    public function updateUser($request): bool
    {
        $imageID = $request->file('image') == null ? $request->get('old_image') : $this->upload($request->file('image'));
        $isActive = ($request->get('isActiveUser') == null) ? 0 : 1;
        $password = ($request->get('GrantType') == 'bl_active_directory' && $request->get('GrantType') != null) ? null : $request->get('password');

        $json = [
            "userID" => $request->get('id'),
            "userName" => $request->get('userName'),
            "fullName" => $request->get('fullName'),
            "password" => $password,
            "emailAddress" => $request->emailAddress,
            "mobileNumber" => $request->get('mobileNumber'),
            "isUserActive" => $isActive,
            "roleIDs" => $request->get('roles'),
            "updatedBy" => Auth::user()->id,
            "permissionIDs" => $request->get('permissions'),
            "profilePicID" => $imageID,
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
        return ($this->SessionCheck('applicationID') == config('nativebl.base.toffee_applicatation_id')) ? true : false;
    }
}
