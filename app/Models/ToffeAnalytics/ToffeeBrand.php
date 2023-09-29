<?php

namespace App\Models\ToffeAnalytics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\ToffeAnalytics\ToffeeBrandUserMap;
class ToffeeBrand extends Model
{
    use HasFactory;
    protected $table = 'toffee_brands';

    public $timestamps = true;

    protected $fillable = ['id','name','icon','created_by'];

    public function brandUserMap() {
    	return $this->hasMany(ToffeeBrandUserMap::class, 'brand_id');
     }
}
