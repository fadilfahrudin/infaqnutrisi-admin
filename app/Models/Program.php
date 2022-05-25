<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    public function owner() {
        return $this->belongsTo('App\Models\Mitra', 'created_by')->withDefault();
    }
}
