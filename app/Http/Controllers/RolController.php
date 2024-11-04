<?php

namespace App\Http\Controllers;
use App\Models\Rol;

use Illuminate\Http\Request;

class RolController extends Controller
{
    public function create(Request $request){
        if($request->isMethod('post')){
            $request->validate([
                'name'=>'required',
                'description'=>'string',
            ],[
                'name.required'=> 'Proporciona el name',
                'description.required'=> 'Proporciona tu apellido',
            ]);
            $rol=new Rol();
            $rol->name = $request->input('name');
            $rol->description = $request->input('description');
            $rol->save();
            return response()->json(['success' => true, 'message' => 'Rol dado de alta']);
        }
    }

    public function update(Request $request, $id){
        $validated= $request->validate([
            'nombre'=> 'required|string|max:40',
            'descripcion'=> 'required|string|max:40',

        ],[
            'nombre.required'=>'Indique el nuedo dato',
            'descripcion.string'=> 'El nombre debe sre una cadena de text',
        ]);

        $rol =Empleados::find($id);


        if(!$rol){
            return response()->json([
                'succes'=> false,
                'message'=> 'Empleado no existente'
            ], 404);
        }
        $rol->name=$request->input('nombre');
        $rol->name=$request->input('descripcion');
        $rol->save();

        return response()->json([
            'success' => true,
            'message' => 'Rol actualizado.',
            'data' => $rol
        ]);

    }
}
