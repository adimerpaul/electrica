<?php

namespace App\Http\Controllers;

use App\Models\Poste;
use App\Http\Requests\StorePosteRequest;
use App\Http\Requests\UpdatePosteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Poste::all();
    }

    public function calcularArea(Request $request){
        $distancia=$request->distancia;
        $lat1=$request->lat;
        $lng1=$request->lng;
        $puntos=Poste::all();
        //return $puntos;
        $enviar=array();
        foreach($puntos as $p){
            $dist=$this->getDistanceBetweenPointsNew($lat1,$lng1,$p->lat,$p->lng,'kilometers');
            //return  $dist;
            if($dist<=$distancia){
                array_push($enviar,$p);
            }
        }
        return $enviar;

   }
   function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2, $unit = 'kilometers') {
    $theta = $longitude1 - $longitude2;
    $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
    $distance = acos($distance);
    $distance = rad2deg($distance);
    $distance = $distance * 60 * 1.1515;
    switch($unit) {
      case 'miles':
        break;
      case 'kilometers' :
        $distance = $distance * 1.609344 *1000;
    }
    return (round($distance,2));
  }
    public function listmtto(){
        return Poste::where('estado','MANTENIMIENTO')->get();
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
     * @param  \App\Http\Requests\StorePosteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePosteRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Poste  $poste
     * @return \Illuminate\Http\Response
     */
    public function show(Poste $poste)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Poste  $poste
     * @return \Illuminate\Http\Response
     */
    public function edit(Poste $poste)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePosteRequest  $request
     * @param  \App\Models\Poste  $poste
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePosteRequest $request, Poste $poste)
    {
        //
        $poste=Poste::find($request->id);
        $poste->estado=$request->estado;
        return $poste->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Poste  $poste
     * @return \Illuminate\Http\Response
     */
    public function destroy(Poste $poste)
    {
        //
    }
}