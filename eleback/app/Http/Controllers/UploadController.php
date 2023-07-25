<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    //
    public function upload(Request $request){
        $this->validate($request, [
            'file'=>'required',
        ]);
        if ($request->hasFile('file')) {
            $file=$request->file('file');
            $nombreArchivo = $file->getClientOriginalName();;
            $file->move(\public_path('archivos'), $nombreArchivo);
        }
        return $nombreArchivo;

    }
}
