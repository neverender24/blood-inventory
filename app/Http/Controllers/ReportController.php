<?php

namespace App\Http\Controllers;

use App\BloodComponent;
use App\Order;
use App\Disposition;
use App\BloodStation;
use App\BloodType;
use App\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct(
        Disposition $disposition,
        Order $order,
        BloodStation $bloodStation,
        BloodType $bloodTypes,
        OrderDetail $orderDetails,
        BloodComponent $bloodComponents
    ) {
        $this->disposition = $disposition;
        $this->order = $order;
        $this->bloodStation = $bloodStation;
        $this->bloodComponents = $bloodComponents;
        $this->bloodTypes = $bloodTypes;
        $this->orderDetails = $orderDetails;
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

    public function report1(Request $request) {
        return $this->orderDetails
            ->select(
                    'blood_types.description',
                    DB::raw('sum(qty) as total'),
                    DB::raw('MONTHNAME(orders.order_date) as month')
                )
            ->leftJoin('orders', 'order_details.order_id', '=', 'orders.id')
            ->leftJoin('blood_types', 'order_details.blood_type_id', '=', 'blood_types.id')
            ->groupBy(DB::raw('MONTHNAME(orders.order_date)'), 'order_details.blood_type_id')
            ->whereYear('orders.order_date', $request->year)
            ->orderBy(DB::raw('MONTH(orders.order_date)'))
            ->get();
    }

    public function report2(Request $request) {
        return $this->bloodStation
                    ->select(
                        'blood_stations.name',
                        DB::raw('SUM( IF(blood_type_id = 8 AND blood_component_id = 1, 1, 0 )) as Opos'),
                        DB::raw('SUM( IF(blood_type_id = 1 AND blood_component_id = 1, 1, 0 )) as Apos'),
                        DB::raw('SUM( IF(blood_type_id = 3 AND blood_component_id = 1, 1, 0 )) as Bpos'),
                        DB::raw('SUM( IF(blood_type_id = 5 AND blood_component_id = 1, 1, 0 )) as ABpos'),
                        DB::raw('SUM( IF(blood_type_id = 7 AND blood_component_id = 1, 1, 0 )) as Oneg'),
                        DB::raw('SUM( IF(blood_type_id = 2 AND blood_component_id = 1, 1, 0 )) as Aneg'),
                        DB::raw('SUM( IF(blood_type_id = 4 AND blood_component_id = 1, 1, 0 )) as Bneg'),
                        DB::raw('SUM( IF(blood_type_id = 5 AND blood_component_id = 1, 1, 0 )) as ABneg'),
                        DB::raw('SUM( IF(blood_type_id = 8 AND blood_component_id = 2, 1, 0 )) as Opospacked'),
                        DB::raw('SUM( IF(blood_type_id = 1 AND blood_component_id = 2, 1, 0 )) as Apospacked'),
                        DB::raw('SUM( IF(blood_type_id = 3 AND blood_component_id = 2, 1, 0 )) as Bpospacked'),
                        DB::raw('SUM( IF(blood_type_id = 5 AND blood_component_id = 2, 1, 0 )) as ABpospacked'),
                        DB::raw('SUM( IF(blood_type_id = 7 AND blood_component_id = 2, 1, 0 )) as Onegpacked'),
                        DB::raw('SUM( IF(blood_type_id = 2 AND blood_component_id = 2, 1, 0 )) as Anegpacked'),
                        DB::raw('SUM( IF(blood_type_id = 4 AND blood_component_id = 2, 1, 0 )) as Bnegpacked'),
                        DB::raw('SUM( IF(blood_type_id = 5 AND blood_component_id = 2, 1, 0 )) as ABnegplasma'),
                        DB::raw('SUM( IF(blood_type_id = 8 AND blood_component_id = 3, 1, 0 )) as Oposplasma'),
                        DB::raw('SUM( IF(blood_type_id = 1 AND blood_component_id = 3, 1, 0 )) as Aposplasma'),
                        DB::raw('SUM( IF(blood_type_id = 3 AND blood_component_id = 3, 1, 0 )) as Bposplasma'),
                        DB::raw('SUM( IF(blood_type_id = 5 AND blood_component_id = 3, 1, 0 )) as ABposplasma'),
                        DB::raw('SUM( IF(blood_type_id = 8 AND blood_component_id = 4, 1, 0 )) as Oposplatelet'),
                        DB::raw('SUM( IF(blood_type_id = 1 AND blood_component_id = 4, 1, 0 )) as Aposplatelet'),
                        DB::raw('SUM( IF(blood_type_id = 3 AND blood_component_id = 4, 1, 0 )) as Bposplatelet'),
                        DB::raw('SUM( IF(blood_type_id = 5 AND blood_component_id = 4, 1, 0 )) as ABposplatelet'),
                        
                    )
                    ->leftJoin('blood_station_disposition',  'blood_station_disposition.blood_station_id', '=',  'blood_stations.id')
                    ->leftJoin('dispositions',  'dispositions.id', '=',  'blood_station_disposition.disposition_id')
                    ->leftJoin('blood_types',  'blood_types.id', '=',  'dispositions.blood_type_id')
                    ->leftJoin('blood_components',  'blood_components.id', '=',  'dispositions.blood_component_id')
                    ->whereYear('dispositions.created_at', $request->year)
                    ->groupBy('blood_stations.id')
                    ->get();

    }

     public function bm6(Request $request) {
        $data = $this->bloodComponents
                ->select(
                    DB::raw('sum( IF(MONTHNAME(dispositions.date_received) = "January" , 1, 0 )) as totalJan'),
                    DB::raw('sum( IF(MONTHNAME(dispositions.date_received) = "February" , 1, 0 )) as totalFeb'),
                    DB::raw('sum( IF(MONTHNAME(dispositions.date_received) = "March" , 1, 0 )) as totalMar'),
                    DB::raw('sum( IF(MONTHNAME(dispositions.date_received) = "April" , 1, 0 )) as totalApr'),
                    DB::raw('sum( IF(MONTHNAME(dispositions.date_received) = "May" , 1, 0 )) as totalMay'),
                    DB::raw('sum( IF(MONTHNAME(dispositions.date_received) = "June" , 1, 0 )) as totalJun'),
                    DB::raw('sum( IF(MONTHNAME(dispositions.date_received) = "July" , 1, 0 )) as totalJul'),
                    DB::raw('sum( IF(MONTHNAME(dispositions.date_received) = "August" , 1, 0 )) as totalAug'),
                    DB::raw('sum( IF(MONTHNAME(dispositions.date_received) = "September" , 1, 0 )) as totalSep'),
                    DB::raw('sum( IF(MONTHNAME(dispositions.date_received) = "October" , 1, 0 )) as totalOct'),
                    DB::raw('sum( IF(MONTHNAME(dispositions.date_received) = "November" , 1, 0 )) as totalNov'),
                    DB::raw('sum( IF(MONTHNAME(dispositions.date_received) = "December" , 1, 0 )) as totalDec'),
                    'blood_components.description',
                    'blood_components.id'
                )
                ->leftJoin('dispositions', 'blood_components.id', '=', 'dispositions.blood_component_id')
                ->whereYear('dispositions.date_received', $request->year)
                ->groupBy('blood_components.id')
                ->orderBy(DB::raw('MONTH(dispositions.date_received)'))
                ->get();

        $emptyBlood = [];
        
        //check tanan available na blood components
        for ($x = 0; $x < 4; $x++) {
           if (!empty($data[$x]['id'])) {
                array_push($emptyBlood, $data[$x]['id']);
            }
        }

        //add blank if wala data
        for ($x = 1; $x <= 4; $x++) {
            if (!in_array($x, $emptyBlood)) {
                $data[] =
                    ["totalJan" => "0",
                    "totalFeb" => "0",
                    "totalMar" => "0",
                    "totalApr" => "0",
                    "totalMay" => "0",
                    "totalJun" => "0",
                    "totalJul" => "0",
                    "totalAug" => "0",
                    "totalSep" => "0",
                    "totalOct" => "0",
                    "totalNov" => "0",
                    "totalDec" => "0",
                    "description" => $this->blood_components($x),
                    "id" => $x];
            }   
        }

        return $data;
    }

    public function blood_components($id) {
        if ($id == 1) {
            return "Whole Blood";
        }

        if ($id == 2) {
            return "Packed RBC";
        }

        if ($id == 3) {
            return "Plasma";
        }

        if ($id == 4) {
            return "Platelet";
        }
    }
}
