<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UploadController extends Controller
{
    //
    public function upload(Request $request){
        $this->validate($request, [
            'file'=>'required',
            'distrito'=>'required',
        ]);
        $res= DB::SELECT("SELECT MAX(id)as numero from actas");
        if(sizeof($res)>0)
            $num=intval( $res[0]->numero) + 1;
        else
            $num=1;
        if($request->id!='')
            $num=$request->id;
        if ($request->hasFile('file')) {
            $file=$request->file('file');
            $nombreArchivo =$request->distrito."-".$num.".".$file->getClientOriginalExtension();
            $file->move(\public_path('archivos'), $nombreArchivo);
        }
        return $nombreArchivo;
    }

    public function uploadImg(Request $request){
        $this->validate($request, [
            'file'=>'required'
        ]);
        $res= DB::SELECT("SELECT MAX(id)as numero from prestamos");
        if(sizeof($res)>0)
            $num=intval( $res[0]->numero) + 1;
        else
            $num=1;
        if ($request->hasFile('file')) {
            $file=$request->file('file');
            $nombreArchivo =$num.".".$file->getClientOriginalExtension();
            $file->move(\public_path('prestamos'), $nombreArchivo);
        }
        return $nombreArchivo;
    }
}
