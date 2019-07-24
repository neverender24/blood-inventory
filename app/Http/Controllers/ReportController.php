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
        
        // return Disposition::with(['order_details'=>function($od){
        //     $od->with(['order'=>function($u){
        //         $u->with(['user'=>function($usr){
        //             $usr->with('bloodStation')->get();
        //         }])->get();
        //     }])
        //         ->get();
        //     }])
        //     ->get();

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
                $expire = $expire->doesntHave('users');
            } else {
                $expire = $expire->whereHas('order_details.order.user', function($wew){
                    $wew->where('blood_station_id', auth()->user()->blood_station_id);
                });
            }
            
            $expire = $expire->get();

            return $expire;
    }

    public function getPendingOrders() {
        return Order::whereHas('user', function($q){
            $q->where('blood_station_id', auth()->user()->blood_station_id)
                ->orWhere('blood_station_id', 5);
        })->whereNull('delivery_date')->count();
    }

    public function getExpiredDispositions(Request $request) {
        //dd(Carbon::now()->addDays(35));
        $expire = Disposition::with(['bloodComponent', 'bloodType','order_details'=>function($od){
                    $od->with(['order'=>function($u){
                        $u->with(['user'=>function($usr){
                            $usr->with('bloodStation')->get();
                        }])->get();
                    }])
                        ->get();
                    }]);

        if ($request['role'] == 'Administrator') {
            $expire = $expire->doesntHave('order_details');
        } else {
            $expire = $expire->whereHas('order_details.order.user', function($wew){
                $wew->where('blood_station_id', auth()->user()->blood_station_id);
            });
        }
        
        $expire = $expire->where( 'date_expiry', '<=', Carbon::now()->addDays(10) )->get();

        return $expire;
    }
}
