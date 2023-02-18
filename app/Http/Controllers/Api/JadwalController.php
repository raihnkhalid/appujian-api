<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AppHelpers;
use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        if (AppHelpers::isAdmin($request->user()->is_admin)) {
            $jadwals = Jadwal::with('mataPelajarans')->get();
            return AppHelpers::JsonApi(200, 'OK', ['message' => 'Successfully fetch data', 'data' => $jadwals]);
        } else {
            $jadwals = Jadwal::where('kelas_id', $request->user()->siswa->kelas_id)->with('mataPelajarans')->get();
            return AppHelpers::JsonApi(200, 'OK', ['message' => 'Successfully fetch data', 'data' => $jadwals]);

        }
    }

    public function store(Request $request)
    {
        if (AppHelpers::isAdmin($request->user()->is_admin)) {
            $validator = Validator::make($request->all(), [
                'hari' => 'required|string',
                'tanggal' => 'required|date',
                'kelas_id' => 'required',
                'matapelajaran' => 'required'
            ]);

            if ($validator->fails()) {
                return AppHelpers::JsonApi(400, "ERROR", ["message" => $validator->errors()]);
            }

            $jadwal = Jadwal::create([
                'hari' => $request->hari,
                'tanggal' => $request->tanggal,
                'kelas_id' => $request->kelas_id
            ]);

            foreach ($request->matapelajaran as $mapel) {
                $matapelajaran = MataPelajaran::create([
                    'nama' => $mapel['nama'],
                    'mulai' => $mapel['mulai'],
                    'selesai' => $mapel['selesai']
                ]);

                $matapelajaran->jadwals()->attach($jadwal->id);
            }

            $result = Jadwal::with('matapelajarans')->find($jadwal->id);

            return AppHelpers::JsonApi(200, 'OK', ['message' => 'Operation Successfully', 'data' => $result]);

        }
    }

    public function destroy(Request $request ,$id)
    {
        if (AppHelpers::isAdmin($request->user()->is_admin)) {
            $jadwal = Jadwal::with('matapelajarans')->find($id);

            if (!$jadwal) {
                return AppHelpers::JsonApi(400, "ERROR", ["message" => "Jadwal not found with this id"]);
            }

            foreach ($jadwal->matapelajarans as $mapel) {
                $matapelajaran = MataPelajaran::find($mapel['id']);
                $matapelajaran->delete();
            }

            $jadwal->delete();
            return AppHelpers::JsonApi(200, "OK", ["message" => "Suuccessfully deleted data!"]);
        }
    }
}
