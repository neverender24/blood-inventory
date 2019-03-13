<?php

namespace App\Http\Controllers;

use App\Order;
use App\Disposition;
use App\BloodStation;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(Order $order) {
        $this->model = $order;
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $sortFields = ['transaction_code'];

		$length = $request->length;
		$column = $request->column;
		$dir = $request->dir;
        $searchValue = $request->search;
        
        $index = $this->model->with(['user', 'order_details'])
                    ->orderBy($sortFields[$column], $dir);

        if (auth()->user()->role != 'Administrator') {
            $index->isOwner();
        }

        if ($searchValue) {
			$index->where(function($query) use($searchValue){
				$query->orWhere('transaction_code','LIKE','%'.$searchValue.'%');
			});
		}

		$index = $index->paginate($length);

    	return ['data'=>$index, 'draw'=> $request->draw];
    }
    

    public function store(Request $request) {

        $request['user_id'] = auth()->user()->id;

        $details = $request->order_details;
        $order = $this->model->create($request->all());

        if ($details) {
            foreach ($details as $detail) {
                $orderDetail = new \App\OrderDetail;
                $orderDetail->qty = $detail['qty'];
                $orderDetail->blood_type_id = $detail['blood_type_id'];
                $orderDetail->blood_component_id = $detail['blood_component_id'];
                $orderDetail->order_id = $order->id;
                $orderDetail->save();
            }
        }

        return $order;
    }

    public function edit($id) {
        return $this->model->with(['user','order_details'=>function($q) {
            $q->with(['blood_type', 'blood_component', 'dispositions'=> function($d){
                $d->with(['bloodComponent', 'bloodType'])->get();
            }]);
        }])->where("id", $id)->first();
    }

    public function update(Request $request, $id) {

        $details = $request->order_details;

        if ($details) {
            foreach ($details as $detail) {
                if ( isset($detail['id'])) {
                    $orderDetail = \App\OrderDetail::find($detail['id']);
                } else {
                    $orderDetail = new \App\OrderDetail;
                    $orderDetail->order_id = $id;
                }
                $orderDetail->qty = $detail['qty'];
                $orderDetail->blood_type_id = $detail['blood_type_id'];
                $orderDetail->blood_component_id = $detail['blood_component_id'];
                $orderDetail->save();
            }
        }

        $edit = $this->model->with(['order_details'])->findOrFail($id);
        $edit->update($request->all());

        return $edit;
    }

    public function destroy($id) {

        $delete = $this->model->findOrFail($id);

        $delete->delete();

        return $delete;
    }

    public function receive(Request $request, $id) {
       
        $details = $request->all();

        foreach($details["order_details"] as $detail => $value) {  
            foreach($value["dispositions"] as $detail => $disp) { 
                $disposition = Disposition::find($disp['id']);

                if (!$disposition->users->contains($details['user']['blood_station_id'])) {
                    $disposition->users()->attach($details['user']['blood_station_id']);
                } 
            }  
        }

        $order = $this->model->findOrFail($id);
        $order->received_date = $request["received_date"];
        $order->received_time = $request["received_time"];
        $order->save();
    }
    
    public function generateCode() {
        $prefix = BloodStation::where('id', auth()->user()->blood_station_id)->value('prefix');

        $totalOrder = $this->model->whereHas('user', function($q){
            $q->where('blood_station_id', auth()->user()->blood_station_id);
        })->whereYear('order_date', '2019')->withTrashed()->count();

        return $prefix."-".date('Y')."-".($totalOrder + 1);
    }


}
