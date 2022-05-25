<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonasiReplies extends Model
{
    protected $table = 'donations_replies';
    public function donasi() {
        return $this->belongsTo('App\Models\Donasi', 'donation_id')->withDefault();
    }
}
