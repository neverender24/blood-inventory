<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Disposition extends Model
{
    protected $fillable = [
        'serial',
        'vol',
        'date_extracted',
        'date_expiry',
        'date_received',
        'received_by',
        'blood_component_id',
        'blood_type_id',
        'user_id'
    ];

    public function bloodType() {
        return $this->belongsTo('App\BloodType');
    }

    public function bloodComponent() {
        return $this->belongsTo('App\BloodComponent');
    }

    public function order_details() {
        return $this->belongsToMany('App\OrderDetail', 'disposition_order_detail');
    }

    public function users() {
        return $this->belongsToMany('App\BloodStation', 'blood_station_disposition');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function blood_station() {
        return $this->belongsTo('App\BloodStation');
    }

    public function releases() {
        return $this->hasMany('App\Release');
    }
}
