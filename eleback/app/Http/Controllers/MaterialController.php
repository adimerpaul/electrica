<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Http\Requests\StoreMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Material::all();
    }

    public function listmaterial(){
        return Material::where('stock','>',0)->get();
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
     * @param  \App\Http\Requests\StoreMaterialRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMaterialRequest $request)
    {
        //
        $material=new Material();
        $material->codigo=strtoupper($request->codigo);
        $material->nombre=strtoupper($request->nombre);
        $material->unidad=$request->unidad;
        $material->stock=0;
        $material->minimo=$request->minimo;
        $material->codificar=$request->codificar;
        $material->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function show(Material $material)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function edit(Material $material)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMaterialRequest  $request
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMaterialRequest $request, Material $material)
    {
        //
        $material=Material::find($request->id);
        $material->codigo=strtoupper($request->codigo);
        $material->nombre=strtoupper($request->nombre);
        $material->unidad=$request->unidad;
        $material->minimo=$request->minimo;
        $material->codificar=$request->codificar;
        $material->color=$request->color;
        $material->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function destroy(Material $material)
    {
        //
        $material->delete();
    }
}
