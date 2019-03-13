<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BloodType extends Model
{
    public function dispositions() {
        return $this->hasMany('App\Disposition');
    }
}
