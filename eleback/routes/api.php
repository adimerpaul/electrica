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
Route::get('buscarPersona/{ci}',[\App\Http\Controllers\PersonaController::class,'buscarPersona']);
Route::get('consultaReclamo/{ci}',[\App\Http\Controllers\PersonaController::class,'consultaReclamo']);
Route::post('calcularArea',[\App\Http\Controllers\PosteController::class,'calcularArea']);
Route::post('login',[\App\Http\Controllers\UserController::class,'login']);
Route::resource('/denuncia',\App\Http\Controllers\DenunciaController::class);
Route::resource('/consulta',\App\Http\Controllers\ConsultaController::class);
Route::resource('persona',\App\Http\Controllers\PersonaController::class);
Route::get('qrFile', [\App\Http\Controllers\InventarioController::class, 'qrFile']);

Route::post('/reclamo',[\App\Http\Controllers\ReclamoController::class,'store']);
    Route::resource('/reclamo',\App\Http\Controllers\ReclamoController::class);
    Route::resource('/salida',\App\Http\Controllers\SalidaController::class);
    Route::get('/recuperaInv/{cod}',[\App\Http\Controllers\InventarioController::class,'recuperaInv']);
    Route::get('/consultaInv/{cod}',[\App\Http\Controllers\InventarioController::class,'consultaInv']);
    Route::post('/consultaBodega',[\App\Http\Controllers\BodegaController::class,'consultaBodega']);
    Route::post('registroMultiple',[\App\Http\Controllers\ReclamoController::class,'registroMultiple']);
    Route::resource('poste',\App\Http\Controllers\PosteController::class);
    Route::post('listActa',[\App\Http\Controllers\ActaController::class,'listActa']);
    Route::get('prediccion/{id}', [\App\Http\Controllers\PosteController::class,'prediccion']);

    Route::group(['middleware'=>'auth:sanctum'],function (){
    Route::post('listaposte',[\App\Http\Controllers\PosteController::class,'listaposte']);
    Route::resource('/user',\App\Http\Controllers\UserController::class);
        Route::resource('/tecnico',\App\Http\Controllers\TecnicoController::class);
        Route::put('/updatePassword/{user}',[\App\Http\Controllers\UserController::class,'updatePassword']);
        Route::put('/updatepermisos/{user}',[\App\Http\Controllers\UserController::class,'updatepermisos']);
        Route::get('/listUser',[\App\Http\Controllers\UserController::class,'listUser']);
        Route::post('/userEstado',[\App\Http\Controllers\UserController::class,'userEstado']);
        Route::resource('/permiso',\App\Http\Controllers\PermisoController::class);
    Route::post('listplan',[\App\Http\Controllers\PosteController::class,'listplan']);
    Route::post('disponible',[\App\Http\Controllers\BodegaController::class,'disponible']);
    Route::post('reporteReclamo',[\App\Http\Controllers\ReclamoController::class,'reporteReclamo']);
    Route::post('reportePoste',[\App\Http\Controllers\ReclamoController::class,'reportePoste']);
    Route::post('logout',[\App\Http\Controllers\UserController::class,'logout']);
    Route::post('upPoste',[\App\Http\Controllers\PosteController::class,'upPoste']);
    Route::post('listsalida',[\App\Http\Controllers\SalidaController::class,'listsalida']);
    Route::post('listsalida2',[\App\Http\Controllers\SalidaController::class,'listsalida2']);
    Route::post('me',[\App\Http\Controllers\UserController::class,'me']);
    Route::get('listmtto/{id}',[\App\Http\Controllers\PosteController::class,'listmtto']);
    Route::get('listAtencion',[\App\Http\Controllers\ReclamoController::class,'listAtencion']);
    Route::get('listAtencion2/{id}',[\App\Http\Controllers\ReclamoController::class,'listAtencion2']);
    Route::get('listPerReclamo',[\App\Http\Controllers\ReclamoController::class,'listPerReclamo']);
    Route::get('tecMant',[\App\Http\Controllers\PosteController::class,'tecMant']);
    Route::resource('/punto',\App\Http\Controllers\PuntoController::class);
    Route::resource('/junta',\App\Http\Controllers\JuntaController::class);
    Route::resource('/agenda',\App\Http\Controllers\AgendaController::class);

    Route::resource('/cronograma',\App\Http\Controllers\CronogramaController::class);
    Route::resource('/tienda',\App\Http\Controllers\TiendaController::class);
    Route::resource('/material',\App\Http\Controllers\MaterialController::class);
    Route::resource('/compra',\App\Http\Controllers\CompraController::class);
    Route::resource('/inventario',\App\Http\Controllers\InventarioController::class);
    Route::resource('/trabajo',\App\Http\Controllers\TrabajoController::class);
    Route::resource('/acta',\App\Http\Controllers\ActaController::class);
    Route::post('listCrono',[\App\Http\Controllers\CronogramaController::class,'listCrono']);
    Route::post('datoImp',[\App\Http\Controllers\CronogramaController::class,'datoImp']);
    Route::get('valCompra/{nro}',[\App\Http\Controllers\CompraController::class,'valCompra']);
    Route::post('buscarInv',[\App\Http\Controllers\InventarioController::class,'buscarInv']);
    Route::post('generatePdf',[\App\Http\Controllers\InventarioController::class,'generatePdf']);
    Route::post('reclamofin',[\App\Http\Controllers\ReclamoController::class,'reclamofin']);
    Route::post('matto',[\App\Http\Controllers\ReclamoController::class,'matto']);
    Route::post('bajaInv',[\App\Http\Controllers\BajaController::class,'bajaInv']);

    Route::post('devolucionMaterial',[\App\Http\Controllers\InventarioController::class,'devolucionMaterial']);
    Route::get('listmaterial',[\App\Http\Controllers\MaterialController::class,'listmaterial']);
    Route::post('inventDisponible',[\App\Http\Controllers\InventarioController::class,'inventDisponible']);
    Route::post('listadoDev',[\App\Http\Controllers\DevueltaController::class,'listadoDev']);
    Route::get('listLugar',[\App\Http\Controllers\TrabajoController::class,'listLugar']);
    Route::post('listaTrabajos',[\App\Http\Controllers\TrabajoController::class,'listaTrabajos']);
    Route::post('reportJob',[\App\Http\Controllers\TrabajoController::class,'reportJob']);

    Route::post('uparchivo',[\App\Http\Controllers\ActaController::class,'uparchivo']);
    Route::get('lugaresActa',[\App\Http\Controllers\ActaController::class,'lugaresActa']);
    Route::get('juntaActa',[\App\Http\Controllers\ActaController::class,'juntaActa']);
    Route::get('vecinoActa',[\App\Http\Controllers\ActaController::class,'vecinoActa']);
    Route::post('/upload', [\App\Http\Controllers\UploadController::class,'upload']);
    Route::post('/uploadImg', [\App\Http\Controllers\UploadController::class,'uploadImg']);

    Route::post('listAgenda', [\App\Http\Controllers\AgendaController::class,'listAgenda']);
    Route::post('cambioFecha', [\App\Http\Controllers\AgendaController::class,'cambioFecha']);
    Route::post('cambioEstado', [\App\Http\Controllers\AgendaController::class,'cambioEstado']);
    Route::post('asignar', [\App\Http\Controllers\AgendaController::class,'asignar']);
    Route::post('finAgenda', [\App\Http\Controllers\AgendaController::class,'finAgenda']);
    Route::post('habilita', [\App\Http\Controllers\AgendaController::class,'habilita']);

    Route::post('reporteMaterial', [\App\Http\Controllers\SalidaController::class,'reporteMaterial']);
    Route::post('reportEntregaMat', [\App\Http\Controllers\SalidaController::class,'reportEntregaMat']);
    Route::post('reportTecnicoMat', [\App\Http\Controllers\SalidaController::class,'reportTecnicoMat']);
    Route::post('reportEntMat', [\App\Http\Controllers\SalidaController::class,'reportEntMat']);

    Route::resource('/grupo',\App\Http\Controllers\GrupoController::class);

//Prestamo de material
    Route::resource('/prestamo',\App\Http\Controllers\PrestamoController::class);
    Route::resource('/prestamodetalle',\App\Http\Controllers\PrestamodetalleController::class);
    Route::resource('/boxtool',\App\Http\Controllers\BoxtoolController::class);
    Route::resource('/tool',\App\Http\Controllers\ToolController::class);
    Route::resource('/bajatool',\App\Http\Controllers\BajaToolController::class);
    Route::post('listPrestamo', [\App\Http\Controllers\PrestamoController::class,'listPrestamo']);
    Route::get('listBox', [\App\Http\Controllers\BoxtoolController::class,'listBox']);

 //Orden de compra ingreso parcial
    Route::resource('/ordencompra',\App\Http\Controllers\OrdencompraController::class);
    Route::resource('/ordendetalle',\App\Http\Controllers\OrdendetalleController::class);
    Route::post('listUnid', [\App\Http\Controllers\OrdencompraController::class,'listUnid']);
    Route::post('listMaterial', [\App\Http\Controllers\OrdencompraController::class,'listMaterial']);
    Route::post('listResp', [\App\Http\Controllers\OrdencompraController::class,'listResp']);
    Route::post('listOrden', [\App\Http\Controllers\OrdencompraController::class,'listOrden']);
    Route::post('listDetalle', [\App\Http\Controllers\OrdendetalleController::class,'listDetalle']);

    Route::post('regLuminiaria', [\App\Http\Controllers\AgendaController::class,'regLuminiaria']);
    Route::post('listRep', [\App\Http\Controllers\AgendaController::class,'listRep']);
    Route::post('listJunta', [\App\Http\Controllers\AgendaController::class,'listJunta']);

    Route::post('listadocompra', [\App\Http\Controllers\CompraController::class,'listadocompra']);
    Route::post('updateEstado/{id}', [\App\Http\Controllers\PosteController::class,'updateEstado']);

    Route::post('materialTecnico/{id}', [\App\Http\Controllers\SalidaController::class,'materialTecnico']);
    Route::post('reporteRecMaterial', [\App\Http\Controllers\ReclamoController::class,'reporteRecMaterial']);
    Route::post('reportTrabMaterial', [\App\Http\Controllers\TrabajoController::class,'reportTrabMaterial']);

//reportTrabMaterial

});

