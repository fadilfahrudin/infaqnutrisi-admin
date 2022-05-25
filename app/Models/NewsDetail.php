<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsDetail extends Model
{
    //
    protected $table = 'news_details';
    public $timestamps = false;

    public function master()
    {
        return $this->belongsTo('App\Models\Master', 'category_id');
    }
}
