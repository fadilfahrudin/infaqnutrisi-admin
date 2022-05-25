<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QurbanOrder extends Model
{
    protected $table = 'qurban_orders';
    public function fundraiser() {
        return $this->belongsTo('App\Models\Mitra', 'refcode', 'refcode')->withDefault();
    }
}
