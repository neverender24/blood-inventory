<?php

namespace App\Http\Controllers;

use App\Order;
use App\Disposition;
use App\BloodStation;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        Disposition $disposition,
        Order $order,
        BloodStation $bloodStation
    ) {
        $this->disposition = $disposition;
        $this->order = $order;
        $this->bloodStation = $bloodStation;
    }

    public function getAllStocks() {
        return $this->bloodStation->with(['dispositions'=>function($q){
                    $q->with(['bloodComponent', 'bloodType'])->doesntHave('releases')->get();
                }])->whereHas('dispositions', function($a){
                    $a->available();
                })->withCount(['dispositions'=>function($q){
                    $q->available();
                }])->get();
    }

    public function getTotalStocks(Request $request) {

        $bloodStationId = auth()->user()->blood_station_id;

        $expire = $this->disposition->withRelationships();

            if ($request['role'] == 'Administrator') {
                $expire = $expire->adminDispositions($bloodStationId);
            } else {
                $expire = $expire->clientDispositions($bloodStationId);
            }
            
            $expire = $expire->available()->get();

            return $expire;
    }

    public function getPendingOrders() {
        return $this->order->whereHas('user', function($q){
            $q->where('blood_station_id', auth()->user()->blood_station_id)
                ->orWhere('blood_station_id', 5);
        })->whereNull('delivery_date')->count();
    }

    public function getNearExpiredDispositions(Request $request) {

        $bloodStationId = auth()->user()->blood_station_id;

        $expire = $this->disposition->withRelationships();

        if ($request['role'] == 'Administrator') {
            $expire = $expire->adminDispositions($bloodStationId);
        } else {
            $expire = $expire->clientDispositions($bloodStationId);
        }
        
        $expire = $expire->doesntHave('releases')
                        ->nearExpiry()
                        ->get();

        return $expire;
    }

    public function getExpiredDispositions(Request $request) {
        
        $bloodStationId = auth()->user()->blood_station_id;

        $expire = $this->disposition->withRelationships();

        if ($request['role'] == 'Administrator') {
            $expire = $expire->adminDispositions($bloodStationId);
        } else {
            $expire = $expire->clientDispositions($bloodStationId);
        }
    
        $expire = $expire->expired()->get();

        return $expire;
    }
}
