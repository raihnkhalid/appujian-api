<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AppHelpers;
use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SiswaController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (AppHelpers::isAdmin($request->user()->is_admin)) {
            $validator = Validator::make($request->all(),
            [
                'namalengkap' => 'required',
                'kelas'       => 'required|integer',
                'noabsen'     => 'required',
                'nis'         => 'required|unique:siswa,nis'
            ]);

            if ($validator->fails()) {
                return AppHelpers::JsonApi(400, "ERROR", ["message" => $validator->errors()]);
            }

            $pass01 = mb_substr($request->get('namalengkap'), 0, 3);
            $password = $pass01 . $request->get('nis');

            $user = User::create([
                'username' => $request->get('nis'),
                'password' => Hash::make($password),
                'is_admin' => '0'
            ]);

            $user_id = $user['id'];

            $siswa = Siswa::create([
                'user_id' => $user_id,
                'namalengkap' => $request->get('namalengkap'),
                'kelas_id' => $request->get('kelas'),
                'noabsen' => $request->get('noabsen'),
                'nis' => $request->get('nis'),
            ]);

            return AppHelpers::JsonApi(200, "OK", ["message" => "Operation Successfully", "data_siswa" => $siswa]);
        }

        // return AppHelpers::JsonApi(401, "Unauthorized", ["message" => 'Invalid Access']);
        return AppHelpers::JsonUnauthorized();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function update($user_id, Request $request, Siswa $siswa)
    {
        if (AppHelpers::isAdmin($request->user()->is_admin)) {
            $validator = Validator::make($request->all(),
            [
                'namalengkap' => 'required',
                'kelas'       => 'required|integer',
                'noabsen'     => 'required',
                'nis'         => 'unique:siswa,nis,'.$user_id.',user_id'
            ]);

            if ($validator->fails()) {
                return AppHelpers::JsonApi(400, "ERROR", ["message" => $validator->errors()]);
            }

            $pass01 = mb_substr($request->get('namalengkap'), 0, 3);
            $password = $pass01 . $request->get('nis');

            // $user = User::create([
            //     'username' => $request->get('nis'),
            //     'password' => Hash::make($password)
            // ]);

            $user = User::find($user_id);
            $user->username = $request->nis;
            $user->password = Hash::make($password);
            $user->save();

            $siswa = Siswa::where('user_id', $user_id)->firstOrFail();
            $siswa->namalengkap = $request->namalengkap;
            $siswa->kelas_id = $request->kelas;
            $siswa->noabsen = $request->noabsen;
            $siswa->nis = $request->nis;
            $siswa->save();


            return AppHelpers::JsonApi(200, "OK", ["message" => "Success Updated Data", "data_siswa" => $siswa]);
        }

        return AppHelpers::JsonUnauthorized();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id, Request $request, Siswa $siswa)
    {
        if (AppHelpers::isAdmin($request->user()->is_admin)) {
            $siswa = Siswa::where('user_id', $user_id)->first();
            if (!$siswa) {
                return AppHelpers::JsonApi(400, "ERROR", ["message" => "Siswa not found"]);
            }
            $siswa->delete();

            $user = User::where('id', $user_id)->firstOrFail();
            $user->delete();

            $user->tokens()->delete();

            return AppHelpers::JsonApi(200, "OK", ["message" => "Success Deleted Data"]);
        }

        return AppHelpers::JsonUnauthorized();
    }

    public function getUsersData(Request $request)
    {
        if (AppHelpers::isAdmin($request->user()->is_admin)) {
            $datasiswa = Siswa::all();

            return AppHelpers::JsonApi(200, "OK", ["message" => "Get Data Success", "data_siswa" => $datasiswa]);
        }

        return AppHelpers::JsonUnauthorized();
    }

    public function getUserData(Request $request)
    {
        $user = $request->user()->id;
        $isadmin = User::where(['id' => $user, 'is_admin' => '0'])->first();
        if ($isadmin) {
            $datasiswa = Siswa::where('user_id', $user)->firstOrFail();
            // return AppHelpers::JsonApi(200, "OK", ["message" => "Get Data Success", "data_siswa" => $datasiswa]);
            return AppHelpers::JsonApi(200, "OK", ["message" => "Get Data Success", "data_siswa" => $datasiswa]);
        }

        $dataadmin = User::where('id', $user)->first();
        return AppHelpers::JsonApi(200, "OK", ["message" => "Get Data Success", "data_siswa" => $dataadmin]);
    }
}
