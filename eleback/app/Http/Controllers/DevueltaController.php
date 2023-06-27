<?php

namespace App\Http\Controllers;

use App\Models\Devuelta;
use App\Http\Requests\StoreDevueltaRequest;
use App\Http\Requests\UpdateDevueltaRequest;

class DevueltaController extends Controller
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
     * @param  \App\Http\Requests\StoreDevueltaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDevueltaRequest $request)
    {
        //

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Devuelta  $devuelta
     * @return \Illuminate\Http\Response
     */
    public function show(Devuelta $devuelta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Devuelta  $devuelta
     * @return \Illuminate\Http\Response
     */
    public function edit(Devuelta $devuelta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDevueltaRequest  $request
     * @param  \App\Models\Devuelta  $devuelta
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDevueltaRequest $request, Devuelta $devuelta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Devuelta  $devuelta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Devuelta $devuelta)
    {
        //
    }
}
