<?php

namespace App\Http\Controllers;

use App\BloodComponent;
use Illuminate\Http\Request;

class BloodComponentController extends Controller
{
    public function __construct(BloodComponent $bloodType) {
        $this->model = $bloodType;
    }

    public function index() {
        return $this->model->all();
    }
}
