<?php

namespace App\Http\Controllers;

use App\BloodType;
use Illuminate\Http\Request;

class BloodTypeController extends Controller
{
    public function __construct(BloodType $bloodType) {
        $this->model = $bloodType;
    }

    public function index() {
        return $this->model->all();
    }
}
