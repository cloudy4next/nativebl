<?php

namespace App\Models\TigerWeb;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\TigerWeb\TagKey;

class FaqTag extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['id','tag_key_id','faq_id','created_at'];

    public function tagKey() {
    	return $this->belongsTo(TagKey::class);
     }
}
