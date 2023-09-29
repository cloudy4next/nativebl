<?php

namespace App\Models\TigerWeb;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagKey extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = ['id','title','slug','created_at', 'updated_at'];
}
