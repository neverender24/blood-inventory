<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password','role','blood_station_id', 'position'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function bloodStation() {
        return $this->belongsTo('App\BloodStation');
    }

    public function dispositions() {
        return $this->belongsToMany('App\Disposition', 'disposition_user');
    }

}
