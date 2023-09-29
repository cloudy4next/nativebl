<?php

namespace App\Models\TigerWeb;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\TigerWeb\Article;
use App\Models\User;

class ArticleReview extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function article() {
    	return $this->belongsTo(Article::class,'articles');
     }

    public function createdBy() {
    	return $this->belongsTo(User::class,'users');
     }
}
