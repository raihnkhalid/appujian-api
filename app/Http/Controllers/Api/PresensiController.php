<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AppHelpers;
use App\Models\Jadwal;
use App\Models\Kehadiran;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $current = Carbon::now();
        $id = $request->user()->id;
        $kelas_id = $request->user()->siswa->kelas_id;

        if (!$jadwal = Jadwal::with('mataPelajarans')->where('kelas_id', $kelas_id)->where('tanggal', $current->format("Y-m-d"))->first()){
            return AppHelpers::JsonApi(200, "OK", ["keterangan" => "Tidak ada ujian pada waktu saat ini, silahkan mengecek jadwal kembali!", "waktu" => $current->format("Y-m-d | H:i:s")]);
        }

        foreach ($jadwal->mataPelajarans as $mapel)
        {
            $mulai = $mapel->mulai;
            if ($current->between($mulai, $mapel->selesai)) {
                $carbonMulai = Carbon::createFromFormat("H:i:s", $mulai);
                $bataspresensi = $carbonMulai->addMinutes(30);

                if (Kehadiran::where('mata_pelajaran_id', $mapel->id)->first()) {
                    return AppHelpers::JsonApi(200, "OK", ["keterangan" => "Anda sudah mengisi presensi untuk mata pelajaran ini!", "id" => $mapel->id]);
                }

                if ($current->between($mulai, $bataspresensi)) {
                    $keterangan = "Hadir";
                } else if($current->gt($bataspresensi)) {
                    $keterangan = "Terlambat";
                }

                Kehadiran::create([
                    'user_id' => $id,
                    'jadwal_id' => $jadwal->id,
                    'mata_pelajaran_id' => $mapel->id,
                    'keterangan' => $keterangan,
                    'jam_hadir' => $current
                ]);

                return AppHelpers::JsonApi(200, "OK", ["message" => "Presensi berhasil!"]);
            }
        }
        return AppHelpers::JsonApi(200, "OK", ["keterangan" => "Tidak ada ujian pada waktu saat ini, silahkan mengecek jadwal kembali!", "waktu" => $current->format("Y-m-d | H:i:s")]);
    }
}
