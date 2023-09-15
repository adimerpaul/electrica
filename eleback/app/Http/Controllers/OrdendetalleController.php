<?php

namespace App\Http\Controllers;

use App\Models\Ordendetalle;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrdendetalleRequest;
use App\Http\Requests\UpdateOrdendetalleRequest;

class OrdendetalleController extends Controller
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
     * @param  \App\Http\Requests\StoreOrdendetalleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrdendetalleRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ordendetalle  $ordendetalle
     * @return \Illuminate\Http\Response
     */
    public function show(Ordendetalle $ordendetalle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ordendetalle  $ordendetalle
     * @return \Illuminate\Http\Response
     */
    public function edit(Ordendetalle $ordendetalle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrdendetalleRequest  $request
     * @param  \App\Models\Ordendetalle  $ordendetalle
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrdendetalleRequest $request, Ordendetalle $ordendetalle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ordendetalle  $ordendetalle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ordendetalle $ordendetalle)
    {
        //
    }
}
