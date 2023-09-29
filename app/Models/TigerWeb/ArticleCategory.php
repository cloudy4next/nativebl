<?php

namespace App\Models\TigerWeb;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\TigerWeb\Article;
use App\Models\User;

class ArticleCategory extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = ['id','title','slug','service_type','description', 'ref_id', 'created_by','updated_by', 'start_date', 'end_date','created_at', 'updated_at'];


    public function parentCategory()
    {
        return $this->belongsTo(ArticleCategory::class, 'ref_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function articles() {
    	return $this->belongsToMany(Article::class,'articles');
     }
}
