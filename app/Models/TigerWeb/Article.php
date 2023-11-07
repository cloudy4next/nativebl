<?php

namespace App\Models\TigerWeb;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\TigerWeb\ArticleCategory;
use App\Models\TigerWeb\ArticleReview;
use App\Models\TigerWeb\ArticleTag;
use App\Models\TigerWeb\TagKey;
use App\Models\TigerWeb\Faq;


class Article extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = ['id','title','slug','article_category_id', 'ref_id', 'service_manager', 'call_disposition_code', 'image', 'link_redirection', 'content', 'complaint_path', 'review_status', 'created_by','updated_by', 'start_date', 'end_date','created_at', 'updated_at'];

    public function articleCategory() {
    	return $this->belongsTo(ArticleCategory::class);
     }
    public function parentArticle() {
        return $this->belongsTo(Article::class, 'ref_id');
     }


     public function parentTree()
    {
        return $this->belongsTo(self::class, 'ref_id')->with('parentTree');
    }

    public function articleReview() {
    	return $this->hasMany(ArticleReview::class);
     }

    public function articleFaq() {
        return $this->hasMany(Faq::class, 'ref_id')->where('faq_type','=', 'ARTICLE');
     }

    public function articleTag() {
    	return $this->hasMany( ArticleTag::class);
     }

    public function updatedByUser() {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
