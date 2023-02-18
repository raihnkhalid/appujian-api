<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Nominasi;
use Illuminate\Http\Request;
use App\Helpers\AppHelpers;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Support\Facades\Validator;

class NominasiController extends Controller
{
    public function index()
    {
        $nominasi = Nominasi::all();
        return AppHelpers::JsonApi(200, "OK", ["message" => "Success get data", "data_nominasi" => $nominasi]);
    }

    public function store(Request $request)
    {
        if (AppHelpers::isAdmin($request->user()->is_admin)) {

            $validator = Validator::make($request->all(),
            [
                'siswa_id' => 'required|integer',
                'kelas_id' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return AppHelpers::JsonApi(400, "ERROR", ["message" => $validator->errors()]);
            }

            $kelas_id = $request->get('kelas_id');
            $absen = Siswa::where('id', $request->get('siswa_id'))->first()->noabsen;
            $tingkat = Kelas::where('id', $kelas_id)->first()->tingkat;

            if ($kelas_id < 10) {
                $kelas_id = sprintf("%02d", $kelas_id);
            } else if ($absen < 10) {
                $absen = sprintf("%02d", $absen);
            }

            $noujian = $tingkat . $kelas_id . $absen;

            $nominasi = Nominasi::create([
                'siswa_id' => $request->get('siswa_id'),
                'kelas_id' => $request->get('kelas_id'),
                'no_ujian' => $noujian
            ]);

            return AppHelpers::JsonApi(200, "OK", ["message" => "Operation Successfully", "data_nominasi" => $nominasi]);

        }
        return AppHelpers::JsonUnauthorized();
    }

    public function destroy($nominasi_id, Request $request)
    {
        if (AppHelpers::isAdmin($request->user()->is_admin)) {
            $nominasi = Nominasi::where('id', $nominasi_id)->first();

            if (!$nominasi) {
                return AppHelpers::JsonApi(400, "ERROR", ["message" => "Nominasi not found with this id"]);
            }

            $nominasi->delete();
            return AppHelpers::JsonApi(200, "OK", ["message" => "Success deleted Nominasi data"]);
        }
        return AppHelpers::JsonUnauthorized();

    }
}
