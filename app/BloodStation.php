<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BloodStation extends Model
{
    public function dispositions() {
        return $this->belongsToMany('App\Disposition', 'blood_station_disposition');
    }

    public function releases() {
        return $this->hasMany('App\Release');
    }
}
