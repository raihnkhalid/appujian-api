<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AppHelpers;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RuanganController extends Controller
{
    public function store(Request $request)
    {
        if (AppHelpers::isAdmin($request->user()->is_admin)) {

            $validator = Validator::make($request->all(),
            [
                'no_ruangan' => 'required|integer|unique:ruangan,no_ruangan',
                'kode_ruangan' => 'required|string|unique:ruangan,kode_ruangan',
                'kelas' => 'required|integer',
                'kapasitas' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return AppHelpers::JsonApi(400, "ERROR", ["message" => $validator->errors()]);
            }

            $ruangan = Ruangan::create([
                'no_ruangan' => $request->get('no_ruangan'),
                'kode_ruangan' => $request->get('kode_ruangan'),
                'kelas_id' => $request->get('kelas'),
                'kapasitas' => $request->get('kapasitas'),
            ]);

            return AppHelpers::JsonApi(200, "OK", ["message" => "Operation Successfully", "data_ruangan" => ["id" => $ruangan->id, "no_ruangan" => $ruangan->no_ruangan, "kode_ruangan" => $ruangan->kode_ruangan, "kelas" => $ruangan->kelas->namakelas, "kapasitas" => $ruangan->kapasitas, "created_at" => $ruangan->created_at, "updated_at" => $ruangan->updated_at]]);
        }

        return AppHelpers::JsonUnauthorized();
    }

    public function update($ruangan_id, Request $request)
    {
        if (AppHelpers::isAdmin($request->user()->is_admin)){
            $ruangan = Ruangan::where('id', $ruangan_id)->first();
            if (!$ruangan) {
                return AppHelpers::JsonApi(400, "ERROR", ["message" => "Ruangan not found with this id"]);
            }

            $validator = Validator::make($request->all(),
            [
                'no_ruangan' => 'required|integer|unique:ruangan,no_ruangan,'.$ruangan_id.',id',
                'kode_ruangan' => 'required|string|unique:ruangan,kode_ruangan,'.$ruangan_id.',id',
                'kelas' => 'required|integer',
                'kapasitas' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return AppHelpers::JsonApi(400, "ERROR", ["message" => $validator->errors()]);
            }

            $ruangan->no_ruangan = $request->no_ruangan;
            $ruangan->kode_ruangan = $request->kode_ruangan;
            $ruangan->kelas_id = $request->kelas;
            $ruangan->kapasitas = $request->kapasitas;
            $ruangan->save();

            return AppHelpers::JsonApi(200, "OK", ["message" => "Success Updated Data", "data_ruangan" => ["id" => $ruangan->id, "no_ruangan" => $ruangan->no_ruangan, "kode_ruangan" => $ruangan->kode_ruangan, "kelas" => $ruangan->kelas->namakelas, "kapasitas" => $ruangan->kapasitas, "created_at" => $ruangan->created_at, "updated_at" => $ruangan->updated_at]]);

        }

        return AppHelpers::JsonUnauthorized();
    }

    public function destroy($ruangan_id, Request $request)
    {
        if (AppHelpers::isAdmin($request->user()->is_admin)) {
            $ruangan = Ruangan::where('id', $ruangan_id)->first();
            if (!$ruangan) {
                return AppHelpers::JsonApi(400, "ERROR", ["message" => "Ruangan not found with this id"]);
            }

            $ruangan->delete();

            return AppHelpers::JsonApi(200, "OK", ["message" => "Success deleted Ruangan"]);
        }
    }

    public function show()
    {
        $ruangan = Ruangan::all();

        return AppHelpers::JsonApi(200, "OK", ["message" => "Success get data", "data_ruangan" => $ruangan]);

    }
}
