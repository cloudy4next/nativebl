<?php

namespace App\Models\ToffeAnalytics;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToffeeCampaign extends Model
{
    use HasFactory;
    protected $table = 'toffee_campaigns';
    protected $fillable = ['campaign_name','campaign_id','agency_id','brand_id', 'user_id', 'created_by'];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
