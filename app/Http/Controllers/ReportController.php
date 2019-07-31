<?php

namespace App\Http\Controllers;

use App\Order;
use Carbon\Carbon;
use App\Disposition;
use App\BloodStation;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function getAllStocks() {
        
            return BloodStation::with(['dispositions'=>function($q){
                        $q->with(['bloodComponent', 'bloodType'])->doesntHave('releases')->get();
                    }])->whereHas('dispositions', function($a){
                        $a->doesntHave('releases');
                    })->withCount(['dispositions'=>function($q){
                        $q->doesntHave('releases');
                    }])->get();
    }

    public function getTotalStocks(Request $request) {

        $expire = Disposition::with(['bloodComponent', 'bloodType','order_details'=>function($od){
            $od->with(['order'=>function($u){
                $u->with(['user'=>function($usr){
                    $usr->with('bloodStation')->get();
                }])->get();
            }])
                ->get();
            }]);

            if ($request['role'] == 'Administrator') {
                $expire = $expire->doesntHave('order_details')->doesntHave('users');
            } else {
                $expire = $expire->whereHas('order_details.order.user', function($wew){
                    $wew->where('blood_station_id', auth()->user()->blood_station_id);
                });
            }
            
            $expire = $expire->where( 'date_expiry', '<=', Carbon::now() )->get();

            return $expire;
    }

    public function getPendingOrders() {
        return Order::whereHas('user', function($q){
            $q->where('blood_station_id', auth()->user()->blood_station_id)
                ->orWhere('blood_station_id', 5);
        })->whereNull('delivery_date')->count();
    }

    public function getNearExpiredDispositions(Request $request) {
        //dd(Carbon::now()->addDays(35));
        $expire = Disposition::with(['bloodComponent', 'bloodType','releases','user','order_details'=>function($od){
                    $od->with(['order'=>function($u){
                        $u->with(['user'=>function($usr){
                            $usr->with('bloodStation')->get();
                        }])->get();
                    }])
                        ->get();
                    }]);

        if ($request['role'] == 'Administrator') {
            $expire = $expire->doesntHave('order_details')->where(function($q){ 
                $q->whereHas('user', function($wew){
                    $wew->where('blood_station_id', auth()->user()->blood_station_id);
                }); 
            });
        } else {
            $expire = $expire->where(function($q){ 
                $q->whereHas('order_details.order.user', function($wew){
                    $wew->where('blood_station_id', auth()->user()->blood_station_id);
                })->whereHas('order_details.order', function($wew){
                    $wew->where('received_date',"!=", null);
                })->orWhereHas('user', function($wew){
                    $wew->where('blood_station_id', auth()->user()->blood_station_id);
                }); 
            });
        }
        
        $expire = $expire->doesntHave('releases')->where(function($q) {
            $q->where('date_expiry', '<=', Carbon::now()->addDays(10))
                ->where('date_expiry', '>=', Carbon::now()->addDays(0));
        })->get();

        return $expire;
    }

    public function getExpiredDispositions(Request $request) {
        //dd(Carbon::now()->addDays(35));
        $expire = Disposition::with(['bloodComponent', 'bloodType','releases','user','order_details'=>function($od){
                    $od->with(['order'=>function($u){
                        $u->with(['user'=>function($usr){
                            $usr->with('bloodStation')->get();
                        }])->get();
                    }])
                        ->get();
                    }]);

        if ($request['role'] == 'Administrator') {
            $expire = $expire->doesntHave('order_details')->where(function($q){ 
                $q->whereHas('user', function($wew){
                    $wew->where('blood_station_id', auth()->user()->blood_station_id);
                }); 
            });
        } else {
            $expire = $expire->where(function($q){ 
                $q->whereHas('order_details.order.user', function($wew){
                    $wew->where('blood_station_id', auth()->user()->blood_station_id);
                })->whereHas('order_details.order', function($wew){
                    $wew->where('received_date',"!=", null);
                })->orWhereHas('user', function($wew){
                    $wew->where('blood_station_id', auth()->user()->blood_station_id);
                }); 
            });
        }
    
        $expire = $expire->doesntHave('releases')->where( 'date_expiry', '<=', Carbon::now() )->get();

        return $expire;
    }
}
