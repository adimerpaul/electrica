<?php

namespace App\Http\Controllers;

use App\Models\Denuncia;
use App\Http\Requests\StoreDenunciaRequest;
use App\Http\Requests\UpdateDenunciaRequest;
use Illuminate\Http\Request;

class DenunciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return Denuncia::all();
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
    public function persona(Request $request){
        return Denuncia::where('ci',$request->ci)->first();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDenunciaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDenunciaRequest $request)
    {
      return Denuncia::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Denuncia  $denuncia
     * @return \Illuminate\Http\Response
     */
    public function show(Denuncia $denuncia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Denuncia  $denuncia
     * @return \Illuminate\Http\Response
     */
    public function edit(Denuncia $denuncia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDenunciaRequest  $request
     * @param  \App\Models\Denuncia  $denuncia
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDenunciaRequest $request, Denuncia $denuncia)
    {
        $denuncia=Denuncia::find($request->id);
//        return $request;
        return $denuncia->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Denuncia  $denuncia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Denuncia $denuncia)
    {
        return $denuncia->delete();
    }
}
