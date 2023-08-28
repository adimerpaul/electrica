<?php

namespace App\Http\Controllers;

use App\Models\Detailacta;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDetailactaRequest;
use App\Http\Requests\UpdateDetailactaRequest;

class DetailactaController extends Controller
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
     * @param  \App\Http\Requests\StoreDetailactaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDetailactaRequest $request)
    {
        //
        $detailacta=new Detailacta;
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Detailacta  $detailacta
     * @return \Illuminate\Http\Response
     */
    public function show(Detailacta $detailacta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Detailacta  $detailacta
     * @return \Illuminate\Http\Response
     */
    public function edit(Detailacta $detailacta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDetailactaRequest  $request
     * @param  \App\Models\Detailacta  $detailacta
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDetailactaRequest $request, Detailacta $detailacta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Detailacta  $detailacta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Detailacta $detailacta)
    {
        //
    }
}
