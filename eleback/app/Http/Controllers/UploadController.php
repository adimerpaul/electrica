<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;

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
            if ($request->hasFile('file')){
                $file    = $request->file('file');
                $nombre     = $num.".".$file->getClientOriginalExtension();
                $ruta=public_path('/prestamos/'.$nombre);
                Image::make($file->getRealPath())
                    ->resize(1200,1200
                        ,function ($constraint){
                            $constraint->aspectRatio();
                        }
                    )
                    ->save($ruta);
            }
            return $nombre;
    }
}
