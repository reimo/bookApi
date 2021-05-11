<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserController extends Controller
{

    public function CreateUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => ['required', 'min:6'],
            'email' => ['unique:users'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        // New subadmin object
        $user = new User;

        // Save to database
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();


        return response()->json($user, 201);
    }
}
