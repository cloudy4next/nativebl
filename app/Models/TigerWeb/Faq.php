<?php

namespace App\Models\TigerWeb;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['id', 'faq_type', 'ref_id', 'question', 'answer', 'created_at', 'created_by'];
}
