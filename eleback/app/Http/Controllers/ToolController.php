<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\Boxtool;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreToolRequest;
use App\Http\Requests\UpdateToolRequest;

class ToolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Tool::with('boxtool')->get();
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
     * @param  \App\Http\Requests\StoreToolRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreToolRequest $request)
    {
        //
        $boxtool=Boxtool::find($request->boxtool_id);
        if($boxtool->tipo=='NO'){
            $tool = Tool::where('boxtool_id',$request->boxtool_id)->get();
            if(sizeof($tool)==0){
                $tool=new Tool;
                $tool->codigo=$request->codigo;
                $tool->nombre=$boxtool->nombre;
                $tool->cantidad=$request->cantidad;
                $tool->saldo=$request->cantidad;
                $tool->estado='ACTIVO';
                $tool->boxtool_id=$request->boxtool_id;
                $tool->save();
            }
            else{
                $tool = Tool::where('boxtool_id',$request->boxtool_id)->first();
                $tool->cantidad=$tool->cantidad + $request->cantidad;
                $tool->saldo=$tool->saldo  + $request->cantidad;
                $tool->estado='ACTIVO';
                $tool->save();
            }
            $boxtool->stock=$boxtool->stock + $request->cantidad;
            $boxtool->disponible=$boxtool->disponible  + $request->cantidad;
            $boxtool->save();
        }
        else{
            $tool=new Tool;
            $tool->codigo=$request->codigo;
            $tool->nombre=$boxtool->nombre;
            $tool->cantidad=1;
            $tool->saldo=1;
            $tool->estado='ACTIVO';
            $tool->observacion=$request->observacion;
            $tool->boxtool_id=$request->boxtool_id;
            $tool->save();
            $boxtool->stock=$boxtool->stock + 1;
            $boxtool->disponible=$boxtool->disponible  + 1;
            $boxtool->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tool  $tool
     * @return \Illuminate\Http\Response
     */
    public function show(Tool $tool)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tool  $tool
     * @return \Illuminate\Http\Response
     */
    public function edit(Tool $tool)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateToolRequest  $request
     * @param  \App\Models\Tool  $tool
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateToolRequest $request, Tool $tool)
    {
        //
        $tool=Tool::find($request->id);
        $tool->codigo=$request->codigo;
        $tool->nombre=$request->nombre;
        $tool->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tool  $tool
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tool $tool)
    {
        //
        $boxtool=Boxtool::find($tool->boxtool_id);
        if($boxtool->disponible  >= $tool->cantidad){
        $boxtool->stock=$boxtool->stock - $tool->cantidad;
        $boxtool->disponible=$boxtool->disponible  -$tool->cantidad;
        $tool->delete();}
    }
}
