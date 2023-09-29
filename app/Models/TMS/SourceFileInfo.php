<?php

namespace App\Models\TMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourceFileInfo extends Model
{
    use HasFactory;

    protected $connection = 'tms_mysql';
    protected $table = 'source_file_info';

}
