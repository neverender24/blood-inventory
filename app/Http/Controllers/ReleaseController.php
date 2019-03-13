<?php

namespace App\Http\Controllers;

use App\Release;
use Illuminate\Http\Request;

class ReleaseController extends Controller
{
    public function __construct(Release $model) {
        $this->model = $model;
    }

    public function store(Request $request) {
        
        $data = $request['dispositions'];
        $request['blood_station_id'] = auth()->user()->blood_station_id;
        $request['user_id'] = auth()->user()->id;

        foreach($data as $save) {
            $request['disposition_id'] = $save['disposition_id'];
            $this->model->create($request->all());
        }
       
    }

    public function getReleaseDispositions(Request $request) {
        $sortFields = ['released_date'];

		$length = $request->length;
		$column = $request->column;
		$dir = $request->dir;
        $searchValue = $request->search;
        $show = $request->show;
        
        //$index = $this->model->with(['bloodType', 'bloodComponent'])->orderBy($sortFields[$column], $dir);

        $index = $this->model->with(['disposition'=>function($d){
            $d->with(['bloodType', 'bloodComponent'])->get();
        }, 'user'])
            ->whereHas('user', function($u){
                $u->where('blood_station_id', auth()->user()->blood_station_id);
            })
            ->orderBy($sortFields[$column], $dir);

        if ($show != "All") {
            $index->where('type', $show);
        }

        if ($searchValue) {
			$index->where(function($query) use($searchValue){
				$query->orWhere('released_date','LIKE','%'.$searchValue.'%');
			});
		}

		$index = $index->paginate($length);

    	return ['data'=>$index, 'draw'=> $request->draw]; 
    }
}
