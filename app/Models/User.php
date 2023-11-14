<?php

namespace App\Models;

use App\Models\ToffeAnalytics\ToffeeAgency;
use App\Models\ToffeAnalytics\ToffeeBrandUserMap;
use App\Traits\APITrait;
use App\Traits\AttachmentUploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HelperTrait;
use App\Models\ToffeAnalytics\ToffeeCampaign;
use App\Models\ToffeAnalytics\ToffeeAgencyUserMap;
use Auth;
use File;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HelperTrait, APITrait, AttachmentUploadTrait;


    // protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'access_token',
        'refresh_token',
        'is_active',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'id' => 'string',
    ];

    public function hasPermission(string $permission): bool
    {
        // dd($permission,$this->userPermission());
        if (in_array($permission, $this->userPermission())) {
            return true;
        }
        return false;
    }

    public function toffeChampaign(): HasMany
    {
        return $this->hasMany(ToffeChampaign::class);
    }

    public function isSuperAdmin(): bool
    {
        $isAdmin = false;
        $userRole = $this->roles();
        foreach ($userRole as $role) {
            if ($role['name'] == "toffe-super-admin") {
                $isAdmin = true;
            }
        }
        return $isAdmin;
    }

    public function isAgency()
    {
        $agency = ToffeeAgencyUserMap::where('user_id', $this->id)->first();
        return ($agency == null) ? false : (int) $agency->agency_id;
    }
    public function isBrand()
    {
        $brand = ToffeeBrandUserMap::where('user_id', $this->id)->first();
        return ($brand == null) ? false : (int) $brand->brand_id;
    }

    public function getToffeeAgencyId(): ?int
    {
        $id = ToffeeAgency::whereRaw('LOWER(name) like ?', ['%toffee%'])->first();
        return  $id == null ? null : (int) $id->id;
    }

    public function camapign()
    {
        $campaignList = [];
        if (Auth::user()->isSuperAdmin()) {
            $query = ToffeeCampaign::all();
        } else {
            if ($this->isAgency()) {
                $query = ToffeeCampaign::where('agency_id', $this->isAgency())->get();
            } else {
                if ($this->getToffeeAgencyId() != null) {
                    $query = ToffeeCampaign::where('brand_id', $this->isBrand())->where('agency_id', $this->getToffeeAgencyId())->get();
                } else {
                    $query = null;
                }
            }
        }
        if ($query == null) {
            return [];
        }
        foreach ($query as $item) {
            $campaignList[] = $item['campaign_id'];
        }
        return array_unique($campaignList);
    }

    public function userImage()
    {
        return $this->getBase64Attachment($this->SessionCheck('profilePicID'));
    }
}
