<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;
use Laravel\Sanctum\Sanctum;


class LoginController extends Controller
{


	public function login(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'password' => ['required', 'min:6'],
			'email' => ['required', 'email']
		]);


		if ($validator->fails()) {
			return response()->json($validator->errors(), 400);
		}


		$user = User::where('email', $request->email)->first();

		if (!$user || !Hash::check($request->password, $user->password)) {
			throw ValidationException::withMessages([
				'email' => ['The provided credentials are incorrect.'],
			]);
		}
		$token = $user->createToken("user-access");

		return [
			'token' => $token->plainTextToken,
			"user" => $user
		];
	}
}
