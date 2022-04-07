<?php

namespace App\Http\Controllers;

use App\Models\Punto;
use App\Http\Requests\StorePuntoRequest;
use App\Http\Requests\UpdatePuntoRequest;

class PuntoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Punto::all();
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
     * @param  \App\Http\Requests\StorePuntoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePuntoRequest $request)
    {
        return Punto::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Punto  $punto
     * @return \Illuminate\Http\Response
     */
    public function show(Punto $punto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Punto  $punto
     * @return \Illuminate\Http\Response
     */
    public function edit(Punto $punto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePuntoRequest  $request
     * @param  \App\Models\Punto  $punto
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePuntoRequest $request, Punto $punto)
    {
        return $punto->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Punto  $punto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Punto $punto)
    {
        return $punto->delete();
    }
}
