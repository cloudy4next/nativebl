<?php

namespace App\Models\ToffeAnalytics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToffeeAgencyUserMap extends Model
{
    use HasFactory;
    protected $table = 'toffee_agency_user_maps';

    public $timestamps = true;

    protected $fillable = ['id','agency_id','user_id','created_by'];
}
