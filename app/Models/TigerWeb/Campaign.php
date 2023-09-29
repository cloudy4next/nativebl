<?php

namespace App\Models\TigerWeb;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    public $timestamps = true;

        protected $fillable = ['id','title','slug','article_id', 'ref_id', 'created_by','updated_by', 'start_date', 'end_date','created_at', 'updated_at'];
}
