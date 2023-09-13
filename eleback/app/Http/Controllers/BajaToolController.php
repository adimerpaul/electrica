<?php

namespace App\Http\Controllers;

use App\Models\BajaTool;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBajaToolRequest;
use App\Http\Requests\UpdateBajaToolRequest;

class BajaToolController extends Controller
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
     * @param  \App\Http\Requests\StoreBajaToolRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBajaToolRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BajaTool  $bajaTool
     * @return \Illuminate\Http\Response
     */
    public function show(BajaTool $bajaTool)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BajaTool  $bajaTool
     * @return \Illuminate\Http\Response
     */
    public function edit(BajaTool $bajaTool)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBajaToolRequest  $request
     * @param  \App\Models\BajaTool  $bajaTool
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBajaToolRequest $request, BajaTool $bajaTool)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BajaTool  $bajaTool
     * @return \Illuminate\Http\Response
     */
    public function destroy(BajaTool $bajaTool)
    {
        //
    }
}
