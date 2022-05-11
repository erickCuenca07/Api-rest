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

 public function index(){
    return User::paginate();
 }

 public function show($id){
    $user= User::find($id);
    return $user;
 }

 public function create(Request $request){
    $validator = Validator::make($request->all(), [
        'user' => 'required',
        'name' => 'required',
        'surname' => 'nullable',
        'email' => 'required|string|email',
        'password' => 'required|string|min:6',
        'rol' => 'required|integer',
    ]);
    
    if($validator->fails()){
        return response()->json($validator->errors()->toJson(),400);
    }
    $user = User::create(array_merge(
        $validator->validate(),
        ['password' => bcrypt($request->password)]
    ));
    $user->save();
    return json_encode(['msg'=>'usuario introducizo correctamente']);
 }

 public function destroy($id){
    User::destroy($id);
     return json_encode(["msg"=>"usuario eliminado correctamente"]);
 }

public function edit(Request $request, $id){
    $users = $request->input('user');
    $name =  $request->input('name');
    $surname =  $request->input('surname');
    $email = $request->input('email');
    $password = $request->input('password');
    $rol =  $request->input('rol');
    User::where('id', $id)->update(
        ['user'=>$users,
         'name'=>$name,
         'surname'=>$surname,
         'email'=>$email,
         'password'=>$password,
         'rol'=>$rol]
      );
    // if ($this->user->rol != "1") {
    //     return response()->error('unauthorized', 401);
    // }

        return json_encode(["msg"=>"Usuario editado correctamente"]);
    
}

}