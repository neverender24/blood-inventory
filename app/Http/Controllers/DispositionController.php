<?php

namespace App\Http\Controllers;

use App\User;
use App\Order;
use App\Disposition;
use App\OrderDetail;
use App\BloodStation;
use Illuminate\Http\Request;

class DispositionController extends Controller
{
    public function __construct(Disposition $model, Order $orders, OrderDetail $od) {
        $this->model        = $model;
        $this->orders       = $orders;
        $this->orderDetails = $od;
    }

    public function index(Request $request) {
        $sortFields     = ['serial'];
		$length         = $request->length;
		$column         = $request->column;
		$dir            = $request->dir;
        $searchValue    = $request->search;
        $show           = $request->show;
        $serial         = $request->serial;

        $index = $this->model->withRelationships()
                    ->doesntHave('users')
                    ->orderBy($sortFields[$column], $dir);

        if ($show == 'available') {
            $index->doesntHave('order_details')->available();
        } else if ($show == 'near_expiry') {
            $index->nearExpiry()->doesntHave('order_details');
        } else if ($show == 'expired') {
            $index->expired()->doesntHave('order_details');
        }

        $this->searchSerial($index, $serial);
        $this->search($index, $searchValue);


        $index = $index->paginate($length);
        $this->addExpiryField($index);

    	return ['data'=>$index, 'draw'=> $request->draw];
    }

    public function addExpiryField($collection) {
        return $collection->map(function($item){
            if ($item->date_expiry <= now()->addDays(10) && $item->date_expiry >= now()->addDays(0)) {
                $item['expiry'] = "Near Expiry";
            } else if ( $item->date_expiry <= now()) {
                $item['expiry'] = "Expired";
            } else {
                $item['expiry'] = "";
            }
        });
    }

    public function search($collection, $searchValue) {

        if ($searchValue) {
            return $collection->where(function($query) use($searchValue){
                $query->orWhere('date_received','LIKE','%'.$searchValue.'%');
            });
        }
    }

    public function searchSerial($collection, $serial) {
        if ($serial) {
			return $collection->where(function($query) use($serial){
				$query->orWhere('serial','LIKE','%'.$serial.'%');
			});
        }
    }

    public function getNoDispositions() {
        return $this->model->with(['bloodType', 'bloodComponent'])->doesntHave('order_details')->get();
    }

    public function getDispositions() {
        return $this->model->whereHas('order_details')->get();
    }

    public function store(Request $request) {
        $request['user_id'] = auth()->user()->id;
        $request['vol']     = (int)$request->vol;

        return $this->model->create($request->all());
    }

    public function storeClient(Request $request) {
        $request['user_id'] = auth()->user()->id;
        $request['vol']     = (int)$request->vol;
        $created            = $this->model->create($request->all());
        $disposition        = $this->model->find($created->id);
        $bloodStationId     = auth()->user()->blood_station_id;

        if (!$disposition->users->contains($bloodStationId)) {
            $disposition->users()->attach($bloodStationId);
        } 
    }

    public function edit($id) {
        return $this->model->where("id", $id)->first();
    }

    public function update(Request $request, $id) {
        $edit = $this->model->findOrFail($id);
        $edit->update($request->all());

        return $edit;
    }

    public function destroy($id) {
        $delete = $this->model->findOrFail($id);
        $delete->delete();

        return $delete;
    }

    public function serveOrder(Request $request) {
        $details                = $request->all();
        $order                  = $this->orders->findOrFail($details["id"]);
        $order->delivery_date   = $details["details"]["delivery_date"];
        $order->delivery_time   = $details["details"]["delivery_time"];
        $order->save();

        foreach($details["orders"] as $detail => $value) { 
            $disposition = $this->model->find($value["disposition_id"]);
            if (! $disposition->order_details->contains($value["order_detail_id"])) {
                $disposition->order_details()->attach($value["order_detail_id"]);
            }
        }

        return $order;
    }

    public function updateOrder(Request $request, $id) {
        $details                = $request->all();
        $order                  = $this->orders->findOrFail($id);
        $order->delivery_date   = $request["details"]["delivery_date"];
        $order->delivery_time   = $request["details"]["delivery_time"];
        $order->save();

        foreach($details["old"] as $detail => $value) { 
            $o = $this->orderDetails->find($value["order_detail_id"]);
            $o->dispositions()->detach();  
        }
        
        foreach($details["orders"] as $detail => $value) { 
            if ($value["disposition_id"]) {
                $disposition = $this->model->find($value["disposition_id"]);
                if (! $disposition->order_details->contains($value["order_detail_id"])) {
                    $disposition->order_details()->attach($value["order_detail_id"]);
                } 
            }
        }

        return $order;
    }

    public function getClientDispositions(Request $request) {
        $sortFields     = ['serial'];
		$length         = $request->length;
		$column         = $request->column;
		$dir            = $request->dir;
        $searchValue    = $request->search;
        $show           = $request->show;
        $serial         = $request->serial;

        $index = $this->model->with(['bloodType', 'bloodComponent'])
            ->whereHas('users', function($u){
                $u->where('blood_station_id', auth()->user()->blood_station_id);
            })
            ->doesntHave('releases')
            ->orderBy($sortFields[$column], $dir);

        $this->searchSerial($index, $serial);
        $index = $index->paginate($length);
        $this->addExpiryField($index);

    	return ['data'=>$index, 'draw'=> $request->draw]; 
    }
}
