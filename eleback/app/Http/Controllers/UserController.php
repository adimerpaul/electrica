<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Permiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function login(Request $request){
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Datos incorrectos o no esta registrado.'],
            ]);
        }
        $user = User::where('email', $request->email)->with('permisos')->firstOrFail();

        return response()->json([
            "token"=>$user->createToken('web')->plainTextToken,
            "user"=>$user
        ]);
    }
    public function me(Request $request){
        $user=User::where('id',$request->user()->id)
        //            ->with('unid')
                    ->with('permisos')
                    ->firstOrFail();
                return $user;   
    }
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
    }

    public function index()
    {
        return User::with('permisos')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
        $user=new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=Hash::make($request->password);
        $permisos= array();
        foreach ($request->permisos as $permiso){
//            echo $permiso['estado'].'  ';
            if ($permiso['estado']==true)
                $permisos[]=$permiso['id'];
        }
        $permiso = Permiso::find($permisos);
        $user->permisos()->attach($permiso);
        return($user);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
        ]);
        $user->update($request->all());
    }
    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|min:6',
        ]);
        $request['password']=Hash::make($request['password']);
        $user->update($request->all());
    }
    public function updatepermisos(Request $request,User $user){
        $permisos= array();
        foreach ($request->permisos as $permiso){
            if ($permiso['estado']==true)
                $permisos[]=$permiso['id'];
        }
        $permiso = Permiso::find($permisos);
        $user->permisos()->detach();
        $user->permisos()->attach($permiso);
    }
    public function destroy(User $user)
    {
        $user->delete();
    }
}
