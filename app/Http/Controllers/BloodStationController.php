<?php

namespace App\Http\Controllers;

use App\BloodStation;
use Illuminate\Http\Request;

class BloodStationController extends Controller
{
    public function __construct(BloodStation $model) {
        $this->model = $model;
    }

    public function index() {
        return $this->model->all();
    }
}
