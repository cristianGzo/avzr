<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function create(Request $request){
        if($request->isMethod('post')){
            $request->validate([
                'correo'=>'required|string',
                'contrasena'=>'required|string',
                'idEmpleado'=>'required',
                'idRol'=>'required',
            ],[
                'correo.required'=> 'Proporciona el correo rol',
                'contrasena.required'=> 'Proporciona una contrasena',
                'idEmpleado'=>'required',
                'idRol' => 'required'
            ]);
            $usuario=new Usuario();
            $usuario->correo = $request->input('correo');
            $usuario->contrasena = $request->input('contrasena');
            $idEmpleado->idEmpleado = $request->input('idEmpleado');
            $idRol->idRol = $request->input('idRol');
            $usuario->save();
            return response()->json(['success' => true, 'message' => 'Usuario dado de alta']);
        }
    }

    public function update(Request $request, $id){
        $validated= $request->validate([
            'correo'=> 'required|string|max:40',
            'contrasena'=> 'string',
            'idRol'=> 'int',
            'idEmpleado'=> 'int'

        ],[
            'correo.required'=>'Debe ser un correo valido una cadena de text',
            'contrasena.string'=> 'Indique la contrasena',
        ]);

        $usuario =Usuario::find($id);


        if(!$usuario){
            return response()->json([
                'succes'=> false,
                'message'=> 'Usuario no existente'
            ], 404);
        }
        $usuario->correo=$request->input('correo');
        $usuario->contrasena=$request->input('contrasena');
        $usuario->idEmpleado=$request->input('idEmpleado');
        $usuario->idRol=$request->input('idRol');
        $usuario->save();

        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado.',
            'data' => $usuario
        ]);
    }
}
