<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('buscarPersona/{ci}',[\App\Http\Controllers\PersonaController::class,'buscarPersona']);
Route::post('consultaReclamo/{ci}',[\App\Http\Controllers\PersonaController::class,'consultaReclamo']);
Route::post('calcularArea',[\App\Http\Controllers\PosteController::class,'calcularArea']);
Route::post('login',[\App\Http\Controllers\UserController::class,'login']);
Route::resource('/denuncia',\App\Http\Controllers\DenunciaController::class);
Route::resource('/consulta',\App\Http\Controllers\ConsultaController::class);
Route::resource('/persona',\App\Http\Controllers\PersonaController::class);
Route::post('/reclamo',[\App\Http\Controllers\ReclamoController::class,'store']);
    Route::resource('/poste',\App\Http\Controllers\PosteController::class);
    Route::resource('/reclamo',\App\Http\Controllers\ReclamoController::class);
    Route::group(['middleware'=>'auth:sanctum'],function (){
        Route::resource('/user',\App\Http\Controllers\UserController::class);
        Route::resource('/tecnico',\App\Http\Controllers\TecnicoController::class);
        Route::put('/updatePassword/{user}',[\App\Http\Controllers\UserController::class,'updatePassword']);
        Route::put('/updatepermisos/{user}',[\App\Http\Controllers\UserController::class,'updatepermisos']);
        Route::resource('/permiso',\App\Http\Controllers\PermisoController::class);

    Route::post('logout',[\App\Http\Controllers\UserController::class,'logout']);
    Route::post('upPoste',[\App\Http\Controllers\PosteController::class,'upPoste']);
    Route::post('me',[\App\Http\Controllers\UserController::class,'me']);
    Route::get('listmtto',[\App\Http\Controllers\PosteController::class,'listmtto']);
    Route::get('listAtencion',[\App\Http\Controllers\ReclamoController::class,'listAtencion']);
    Route::resource('/punto',\App\Http\Controllers\PuntoController::class);

});

