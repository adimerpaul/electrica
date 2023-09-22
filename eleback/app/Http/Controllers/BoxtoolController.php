<?php

namespace App\Http\Controllers;

use App\Models\Boxtool;
use App\Models\Tool;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBoxtoolRequest;
use App\Http\Requests\UpdateBoxtoolRequest;

class BoxtoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Boxtool::all();
    }

    public function listBox(){
        return Boxtool::with('toolsactivo')->where('disponible','>',0)->get();
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
     * @param  \App\Http\Requests\StoreBoxtoolRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBoxtoolRequest $request)
    {
        //
        $boxtool= new Boxtool;
        $boxtool->stock=0;
        $boxtool->disponible=0;
        $boxtool->nombre=$request->nombre;
        $boxtool->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Boxtool  $boxtool
     * @return \Illuminate\Http\Response
     */
    public function show(Boxtool $boxtool)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Boxtool  $boxtool
     * @return \Illuminate\Http\Response
     */
    public function edit(Boxtool $boxtool)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBoxtoolRequest  $request
     * @param  \App\Models\Boxtool  $boxtool
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBoxtoolRequest $request, Boxtool $boxtool)
    {
        //
        $boxtool= Boxtool::find($request->id);
        $boxtool->nombre=$request->nombre;
        $boxtool->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Boxtool  $boxtool
     * @return \Illuminate\Http\Response
     */
    public function destroy(Boxtool $boxtool)
    {
        //
        $boxtool->delete();

    }
}
