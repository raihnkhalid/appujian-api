<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AppHelpers;
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(AppHelpers::isAdmin($request->user()->is_admin)){

            $validator = Validator::make($request->all(),
            [
                'namakelas' => 'required|string|unique:kelas,namakelas',
                'jurusan' => 'required|string',
                'tingkat' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return AppHelpers::JsonApi(400, "ERROR", ["message" => $validator->errors()]);
            }

            $kelas = Kelas::create([
                'namakelas' => $request->get('namakelas'),
                'jurusan' => $request->get('jurusan'),
                'tingkat' => $request->get('tingkat'),
            ]);

            return AppHelpers::JsonApi(200, "OK", ["message" => "Operation Successfully"]);
        }

        return AppHelpers::JsonUnauthorized();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kelas  $kelas
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Kelas $kelas)
    {
        $kelas = Kelas::all();
        return AppHelpers::JsonApi(200, "OK", ["message" => "Success get data", "data_kelas" => $kelas]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kelas  $kelas
     * @return \Illuminate\Http\Response
     */
    public function update($kelas_id, Request $request, Kelas $kelas)
    {
        if (AppHelpers::isAdmin($request->user()->is_admin)) {
            $validator = Validator::make($request->all(),
            [
                'namakelas' => 'required|string|unique:kelas,namakelas,'.$kelas_id,
                'jurusan' => 'required|string',
                'tingkat' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return AppHelpers::JsonApi(400, 'Bad_Requests', ['message' => $validator->errors()]);
            }

            $kelas = Kelas::where('id', $kelas_id)->firstOrFail();
            $kelas->namakelas = $request->get('namakelas');
            $kelas->jurusan = $request->get('jurusan');
            $kelas->tingkat = $request->get('tingkat');
            $kelas->save();

            return AppHelpers::JsonApi(200, 'OK', ['message' => 'Operation Successfully']);
        }

        return AppHelpers::JsonUnauthorized();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kelas  $kelas
     * @return \Illuminate\Http\Response
     */
    public function destroy($kelas_id, Request $request, Kelas $kelas)
    {
        if (AppHelpers::isAdmin($request->user()->is_admin)) {
            $kelas = Kelas::where('id', $kelas_id)->first();
            if (!$kelas) {
                return AppHelpers::JsonApi(400, "ERROR", ["message" => "Kelas not found"]);
            }

            $kelas->delete();

            return AppHelpers::JsonApi(200, "OK", ["message" => "Success deleted kelas"]);
        }
    }
}
