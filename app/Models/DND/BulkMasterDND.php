<?php

namespace App\Models\DND;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkMasterDND extends Model
{
    use HasFactory;

    protected $connection = 'dnd_mysql';
    protected $table = 'dnd_bulk_master';

    protected $guarded = [];
    public $timestamps = false;

}
