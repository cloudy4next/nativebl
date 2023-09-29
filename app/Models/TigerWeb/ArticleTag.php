<?php

namespace App\Models\TigerWeb;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\TigerWeb\TagKey;

class ArticleTag extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['id','tag_key_id','article_id','created_at'];

    public function tagKey() {
    	return $this->belongsTo(TagKey::class);
     }
}
