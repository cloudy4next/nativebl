<?php

namespace App\Models\DND;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DNDHistory extends Model
{
    use HasFactory;

    protected $connection = 'dnd_mysql';
    protected $table = 'dnd_history';
}
