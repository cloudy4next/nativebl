<?php

namespace App\Models\ToffeAnalytics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToffeeBrandUserMap extends Model
{
    use HasFactory;
    protected $table = 'toffee_brand_user_maps';

    public $timestamps = true;

    protected $fillable = ['id','brand_id','user_id','created_by'];
}
