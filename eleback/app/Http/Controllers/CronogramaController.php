<?php

namespace App\Http\Controllers;

use App\Models\Cronograma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\StoreCronogramaRequest;
use App\Http\Requests\UpdateCronogramaRequest;

class CronogramaController extends Controller
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

    public function listCrono(Request $request){
        return Cronograma::whereDate('fecha','>=',$request->ini)->whereDate('fecha','<=',$request->fin)->orderBy('fecha','asc')->get();
    }

    public function datoImp(Request $request){
        return DB::SELECT("SELECT codigo,
        actividad,
        distrito,
        junta,
        zona,
        '100' valor1,
        '100' valor2,
        'Puntos de Iluminacion' unidad,
        tipo,
        sum(cantidad) total
                 from cronogramas where date(fecha)>='$request->ini' and date(fecha)<='$request->fin'
        group by codigo,actividad,distrito,zona,junta,tipo");
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCronogramaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCronogramaRequest $request)
    {
        //
        return Cronograma::create($request->all());

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cronograma  $cronograma
     * @return \Illuminate\Http\Response
     */
    public function show(Cronograma $cronograma)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cronograma  $cronograma
     * @return \Illuminate\Http\Response
     */
    public function edit(Cronograma $cronograma)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCronogramaRequest  $request
     * @param  \App\Models\Cronograma  $cronograma
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCronogramaRequest $request, Cronograma $cronograma)
    {
        //
        return $cronograma->update($request->all());

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cronograma  $cronograma
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cronograma $cronograma)
    {
        //
        $cronograma->delete();
    }
}
