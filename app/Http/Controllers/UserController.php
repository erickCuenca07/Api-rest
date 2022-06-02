<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

 public function getAll(){
    return User::paginate();
 }

 public function getOne($id){
    $user= User::find($id);
    return $user;
 }

 public function create(Request $request){
    $user = new User();
    $user->user = $request->user;
    $user->name = $request->name;
    $user->surname=$request->surname;
    $user->email = $request->email;
    $user->password = bcrypt($request->password);
    $user->rol = $request->rol;

    if( $request->hasFile('imagen') ) {
        $file = $request->file('imagen');
        $destinationPath = 'imagen/';
        $filename = time() . '-' . $file->getClientOriginalName();
        $uploadSuccess = $request->file('imagen')->move($destinationPath, $filename);
        $user->imagen = $destinationPath . $filename;
    }
    $user->save();
    return response()->json([
        'message' => '¡Usuario registrado exitosamente!',
        'user' => $user
    ]);
 }

 public function destroy($id){
    User::destroy($id);
     return response()->json(["message"=>"usuario eliminado correctamente"]);
 }

public function edit(Request $request, $id){
    $user = User::find($id);
    $user->user = $request->user;
    $user->name = $request->name;
    $user->surname=$request->surname;
    $user->email = $request->email;
    $user->password = bcrypt($request->password);
    $user->rol = $request->rol;

    if( $request->hasFile('imagen') ) {
        $file = $request->file('imagen');
        $destinationPath = 'imagen/';
        $filename = time() . '-' . $file->getClientOriginalName();
        $uploadSuccess = $request->file('imagen')->move($destinationPath, $filename);
        $user->imagen = $destinationPath . $filename;
    }
    $user->update();
    return response()->json([
        'message' => '¡Usuario editado exitosamente!',
        'user' => $user
    ]);;
    
}

public function editImage(Request $request, $id){
    $user = User::find($id);

    if( $request->hasFile('imagen') ) {
        $file = $request->file('imagen');
        $destinationPath = 'imagen/';
        $filename = time() . '-' . $file->getClientOriginalName();
        $uploadSuccess = $request->file('imagen')->move($destinationPath, $filename);
        $user->imagen = $destinationPath . $filename;
    }
    $user->update();
    return response()->json([
        'message' => '¡Imagen actualizada exitosamente!',
        'user' => $user
    ]);  
}

public function destroyImg($id){
    $user=User::find($id);
    $user->imagen = null;
    $user->update();
    return response()->json([
        "message"=>"Imagen del usuario eliminado correctamente",
        "user" => $user
    ]);
 }

}