<?php

namespace App\Http\Controllers;

use App\Models\Ordencompra;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrdencompraRequest;
use App\Http\Requests\UpdateOrdencompraRequest;

class OrdencompraController extends Controller
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
     * @param  \App\Http\Requests\StoreOrdencompraRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrdencompraRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ordencompra  $ordencompra
     * @return \Illuminate\Http\Response
     */
    public function show(Ordencompra $ordencompra)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ordencompra  $ordencompra
     * @return \Illuminate\Http\Response
     */
    public function edit(Ordencompra $ordencompra)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrdencompraRequest  $request
     * @param  \App\Models\Ordencompra  $ordencompra
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrdencompraRequest $request, Ordencompra $ordencompra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ordencompra  $ordencompra
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ordencompra $ordencompra)
    {
        //
    }
}
