<?php

namespace App\Http\Controllers;

use App\Models\Prestamodetalle;
use App\Models\Prestamo;
use App\Models\Tool;
use App\Models\Boxtool;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePrestamodetalleRequest;
use App\Http\Requests\UpdatePrestamodetalleRequest;

class PrestamodetalleController extends Controller
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
     * @param  \App\Http\Requests\StorePrestamodetalleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePrestamodetalleRequest $request)
    {
        //
        $prestamodetalle= new Prestamodetalle;
        $prestamodetalle->fechadev=$request->fechadev;
        $prestamodetalle->horadev=date("H:i:s");
        $prestamodetalle->observacion=$request->observacion;
        $prestamodetalle->usuario=$request->user()->name;
        $prestamodetalle->prestamo_id=$request->prestamo_id;
        $prestamodetalle->save();

        $prestamo=Prestamo::find($request->prestamo_id);
        $prestamo->estado='DEVUELTO';
        $prestamo->save();

        $tool=Tool::find($prestamo->tool_id);
        $tool->estado='ACTIVO';
        $tool->save();

        $boxtool=Boxtool::find($tool->boxtool_id);
        $boxtool->disponible = $boxtool->disponible + 1;
        $boxtool->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Prestamodetalle  $prestamodetalle
     * @return \Illuminate\Http\Response
     */
    public function show(Prestamodetalle $prestamodetalle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prestamodetalle  $prestamodetalle
     * @return \Illuminate\Http\Response
     */
    public function edit(Prestamodetalle $prestamodetalle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePrestamodetalleRequest  $request
     * @param  \App\Models\Prestamodetalle  $prestamodetalle
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePrestamodetalleRequest $request, Prestamodetalle $prestamodetalle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prestamodetalle  $prestamodetalle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prestamodetalle $prestamodetalle)
    {
        //
    }
}
