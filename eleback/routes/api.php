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

Route::get('buscarPoste/{nro}',[\App\Http\Controllers\PosteController::class,'buscarPoste']);
Route::post('buscarPersona/{ci}',[\App\Http\Controllers\PersonaController::class,'buscarPersona']);
Route::post('consultaReclamo/{ci}',[\App\Http\Controllers\PersonaController::class,'consultaReclamo']);
Route::post('calcularArea',[\App\Http\Controllers\PosteController::class,'calcularArea']);
Route::post('login',[\App\Http\Controllers\UserController::class,'login']);
Route::resource('/denuncia',\App\Http\Controllers\DenunciaController::class);
Route::resource('/consulta',\App\Http\Controllers\ConsultaController::class);
Route::resource('/persona',\App\Http\Controllers\PersonaController::class);
Route::get('qrFile', [\App\Http\Controllers\InventarioController::class, 'qrFile']);
Route::post('/reclamo',[\App\Http\Controllers\ReclamoController::class,'store']);
    Route::resource('/reclamo',\App\Http\Controllers\ReclamoController::class);
    Route::resource('/salida',\App\Http\Controllers\SalidaController::class);
    Route::get('/recuperaInv/{cod}',[\App\Http\Controllers\InventarioController::class,'recuperaInv']);
    Route::get('/consultaInv/{cod}',[\App\Http\Controllers\InventarioController::class,'consultaInv']);
    Route::post('/registroMultiple',[\App\Http\Controllers\ReclamoController::class,'registroMultiple']);
    Route::resource('/poste',\App\Http\Controllers\PosteController::class);
    Route::group(['middleware'=>'auth:sanctum'],function (){
    Route::post('listaposte',[\App\Http\Controllers\PosteController::class,'listaposte']);
    Route::resource('/user',\App\Http\Controllers\UserController::class);
        Route::resource('/tecnico',\App\Http\Controllers\TecnicoController::class);
        Route::put('/updatePassword/{user}',[\App\Http\Controllers\UserController::class,'updatePassword']);
        Route::put('/updatepermisos/{user}',[\App\Http\Controllers\UserController::class,'updatepermisos']);
        Route::get('/listUser',[\App\Http\Controllers\UserController::class,'listUser']);
        Route::resource('/permiso',\App\Http\Controllers\PermisoController::class);
    Route::post('listplan',[\App\Http\Controllers\PosteController::class,'listplan']);
    Route::post('disponible',[\App\Http\Controllers\BodegaController::class,'disponible']);
    Route::post('reporteReclamo',[\App\Http\Controllers\ReclamoController::class,'reporteReclamo']);
    Route::post('logout',[\App\Http\Controllers\UserController::class,'logout']);
    Route::post('upPoste',[\App\Http\Controllers\PosteController::class,'upPoste']);
    Route::post('listsalida',[\App\Http\Controllers\SalidaController::class,'listsalida']);
    Route::post('me',[\App\Http\Controllers\UserController::class,'me']);
    Route::get('listmtto',[\App\Http\Controllers\PosteController::class,'listmtto']);
    Route::get('listAtencion',[\App\Http\Controllers\ReclamoController::class,'listAtencion']);
    Route::resource('/punto',\App\Http\Controllers\PuntoController::class);
    Route::resource('/junta',\App\Http\Controllers\JuntaController::class);

    Route::resource('/cronograma',\App\Http\Controllers\CronogramaController::class);
    Route::resource('/tienda',\App\Http\Controllers\TiendaController::class);
    Route::resource('/material',\App\Http\Controllers\MaterialController::class);
    Route::resource('/compra',\App\Http\Controllers\CompraController::class);
    Route::resource('/inventario',\App\Http\Controllers\InventarioController::class);
    Route::post('listCrono',[\App\Http\Controllers\CronogramaController::class,'listCrono']);
    Route::post('datoImp',[\App\Http\Controllers\CronogramaController::class,'datoImp']);
    Route::get('valCompra/{nro}',[\App\Http\Controllers\CompraController::class,'valCompra']);
    Route::post('buscarInv',[\App\Http\Controllers\InventarioController::class,'buscarInv']);
    Route::post('generatePdf',[\App\Http\Controllers\InventarioController::class,'generatePdf']);
    Route::post('reclamofin',[\App\Http\Controllers\ReclamoController::class,'reclamofin']);
    Route::post('matto',[\App\Http\Controllers\ReclamoController::class,'matto']);


});

