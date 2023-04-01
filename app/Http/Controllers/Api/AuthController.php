<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AppHelpers;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return AppHelpers::JsonApi(400, "ERROR", ["message" => $validator->errors()]);
        }

        if (!Auth::attempt(['username' => $request->get('username'),
                            'password' => $request->get('password'),
                            'is_admin' => '0'])) {
            return AppHelpers::JsonApi(401, "ERROR", ["message" => 'Incorrect Credentials']);
        }

        $user = User::where('username', $request->username)->firstOrFail();
        $token = $user->createToken('auth_token', ['user'], now()->addDays(3));
        $datasiswa = Siswa::where('user_id', $request->user()->id)->firstOrFail();

        return AppHelpers::JsonApi(200, "OK", ["message" => "Login Success", "data_siswa" => $datasiswa, "token_type" => "Bearer", "token" => $token->plainTextToken, 'expires_at' => $token->accessToken->expires_at]);
    }

    public function adminLogin(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return AppHelpers::JsonApi(400, "ERROR", ["message" => $validator->errors()]);
        }

        $login = Auth::attempt(['username' => $request->get('username'),
                                'password' => $request->get('password'),
                                'is_admin' => 1]);

        if (!$login) {
            return AppHelpers::JsonApi(401, "ERROR", ["message" => 'Incorrect Credentials']);
        }

        $user = User::where('username', $request->username)->firstOrFail();
        $token = $user->createToken('auth_token', ['admin'], now()->addHours(744));

        return AppHelpers::JsonApi(200, "OK", ["message" => "Login Admin Success", "data_admin" => $user, "token_type" => "Bearer", "token" => $token->plainTextToken, 'expires_at' => $token->accessToken->expires_at]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return AppHelpers::JsonApi(200, "OK", ["message" => "Logout Success"]);
    }

    public function registerAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return AppHelpers::JsonApi(400, "Bad_Requests", ["message" => $validator->errors()]);
        }

        $user = User::create([
            'username' => $request->get('username'),
            'password' => Hash::make($request->get('password'))
        ]);

        $user2 = User::where('is_admin', 1)->firstOrFail();

        return AppHelpers::JsonApi(200, "OK", ["message" => "Operation Successfully", "data_admin" => $user2]);
    }
}
