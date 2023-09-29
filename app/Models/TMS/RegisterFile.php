<?php

namespace App\Models\TMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterFile extends Model
{
    use HasFactory;


    protected $connection = 'tms_mysql';
    protected $table = 'register_files';
}
