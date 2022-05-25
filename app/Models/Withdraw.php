<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    //
    public function program()
    {
        return $this->belongsTo('App\Models\Program', 'program_id')->withDefault();
    }
}


