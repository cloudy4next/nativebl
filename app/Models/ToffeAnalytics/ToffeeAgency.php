<?php

namespace App\Models\ToffeAnalytics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\ToffeAnalytics\ToffeeAgencyUserMap;

class ToffeeAgency extends Model
{
    use HasFactory;
    protected $table = 'toffee_agencies';

    public $timestamps = true;

    protected $fillable = ['id','name','icon','created_by'];


    public function agencyUserMap() {
    	return $this->hasMany(ToffeeAgencyUserMap::class, 'agency_id');
     }

}
