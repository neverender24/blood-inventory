<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function changePassword(Request $request)
    {

        $old = request()->get('old');
        $new = request()->get('new');
        $confirm = request()->get('confirm');

        if (!Hash::check($old, auth()->user()->password)) {
            return response()->json(array(
                'code' => 400,
                'message' => "Password given incorrect",
            ), 400);
        }

        if ($new !== $confirm) {
            return response()->json(array(
                'code' => 401,
                'message' => "New password not matched",
            ), 401);
        }

        $user = $this->model->findOrFail(auth()->user()->id);
        $user->password = bcrypt($new);
        $user->save();

        return $user;
    }

    public function getUser()
    {
        return $this->model
            ->with(['bloodStation'])
            ->where('id', auth()->user()->id)
            ->first();
    }

    public function welcome()
    {
        return view('welcome');
    }
}
