<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Release extends Model
{
    protected $fillable = [
        'disposition_id',
        'blood_station_id',
        'user_id',
        'remarks',
        'released_by',
        'released_date',
        'released_time',
        'type',
    ];

    public function disposition() {
        return $this->belongsTo('App\Disposition');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function blood_station() {
        return $this->belongsTo('App\BloodStation');
    }
}
