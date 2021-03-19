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
        'user_id',
        'blood_station_id'
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

    public function scopeClientDispositions($query, $bloodStationId) {
        return $query->where(function($q) use($bloodStationId){ 
                $q->whereHas('order_details.order.user', function($wew) use($bloodStationId){
                    $wew->where('blood_station_id', $bloodStationId);
                })->whereHas('order_details.order', function($wew){
                    $wew->where('received_date',"!=", null);
                })->orWhereHas('user', function($wew) use($bloodStationId){
                    $wew->where('blood_station_id', $bloodStationId);
                }); 
            });
    }

    public function scopeWithRelationships($query) {
        return $query->with(['bloodComponent', 'bloodType','releases','user','order_details'=>function($od){
                    $od->with(['order'=>function($u){
                        $u->with(['user'=>function($usr){
                            $usr->with('bloodStation')->get();
                        }])->get();
                    }])
                        ->get();
                    }]);
    }

    public function scopeAdminDispositions($query, $bloodStationId) {
        return $query->doesntHave('order_details')->where(function($q) use($bloodStationId){ 
                $q->whereHas('user', function($wew) use($bloodStationId){
                    $wew->where('blood_station_id', $bloodStationId);
                }); 
            });
    }

    public function scopeNearExpiry($query) {
        return $query->where(function($q) {
            $q->where('date_expiry', '<=', now()->addDays(10))
                ->where('date_expiry', '>=', now()->addDays(0));
        });
    }

    public function scopeAvailable($query) {
        return $query->doesntHave('releases')->where( 'date_expiry', '>', now() );
    }

    public function scopeExpired($query) {
        return $query->doesntHave('releases')->where('date_expiry', '<=', now());
    }
}
