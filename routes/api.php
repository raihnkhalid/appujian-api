<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KelasController;
use App\Http\Controllers\Api\SiswaController;
use App\Http\Controllers\Api\RuanganController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Route Group for valid access token only
Route::group(['middleware' => 'auth:sanctum'], function() {
    // Route for create, update, and delete data siswa.
    Route::post('/siswa/insert', [SiswaController::class, 'store']); //done doc
    Route::post('/siswa/update/{user_id}', [SiswaController::class, 'update']); //done doc
    Route::get('/siswa/delete/{user_id}', [SiswaController::class, 'destroy']); //done doc

    // Route for create, update, and delete data kelas.
    Route::post('/kelas/insert', [KelasController::class, 'store']); //done doc
    Route::post('/kelas/update/{kelas_id}', [KelasController::class, 'update']); //done doc
    Route::get('/kelas/delete/{kelas_id}', [KelasController::class, 'destroy']); //done doc

    // Route for get user/siswa data and logout.
    Route::get('/user/logout', [AuthController::class, 'logout']); //done doc
    Route::get('/users', [SiswaController::class, 'getUsersData']); //done doc
    Route::get('/user', [SiswaController::class, 'getUserData']); //done doc

    // Route for create, update, and delete data ruangan.
    Route::post('/ruangan/insert', [RuanganController::class, 'store']);
    Route::post('/ruangan/update/{ruangan_id}', [RuanganController::class, 'update']);
    Route::get('/ruangan/delete/{ruangan_id}', [RuanganController::class, 'destroy']);
});

// Route for register and login admin, for register route can only be used once. If there already exist admin accoynt, this route cannot be used
Route::post('/admin/register', [AuthController::class, 'registerAdmin'])->middleware('restrictors'); //done doc
Route::post('/admin/login', [AuthController::class, 'adminLogin']); //done doc

// Route for user/siswa login
Route::post('/user/login', [AuthController::class, 'login']); //done doc

// Route for get kelas data
Route::get('/kelas', [KelasController::class, 'show']); //done doc
Route::get('/ruangan', [RuanganController::class, 'show']);

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('optimize');
    return 'Application cache cleared';
});

