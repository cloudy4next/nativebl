<?php

namespace App\Models\DND;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkMasterDetail extends Model
{
    use HasFactory;

    protected $connection = 'dnd_mysql';
    protected $table = 'dnd_bulk_details';

    protected $guarded = [];
    public $timestamps = false;


}
