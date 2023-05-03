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
        return Cronograma::with('junta')->whereDate('fecha','>=',$request->ini)->whereDate('fecha','<=',$request->fin)->orderBy('fecha','asc')->get();
    }

    public function datoImp(Request $request){
        return DB::SELECT("SELECT c.codigo,
        c.actividad,
        j.distrito,
        j.nombre,
        j.zona,
        '100' valor1,
        '100' valor2,
        'Puntos de Iluminacion' unidad,
        c.tipo,
        c.descripcion detalle,
        sum(cantidad) total
        from cronogramas c inner join juntas j on c.junta_id=j.id
        where date(c.fecha)>='$request->ini' and date(c.fecha)<='$request->fin'
        group by c.codigo,c.actividad,j.distrito,j.zona,j.nombre,c.tipo,c.descripcion");
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
        $cronograma = Cronograma::find($request->id);
        $cronograma->codigo=$request->codigo;
        $cronograma->actividad=$request->actividad;
        $cronograma->celular=$request->celular;
        $cronograma->tipo=$request->tipo;
        $cronograma->cantidad=$request->cantidad;
        $cronograma->fecha=$request->fecha;
        $cronograma->descripcion=$request->descripcion;
        $cronograma->junta_id=$request->junta_id;
        $cronograma->save();

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
