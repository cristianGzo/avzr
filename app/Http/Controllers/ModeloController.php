<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modelo;

class ModeloController extends Controller
{
    public function create(Request $request){
        if($request->isMethod('post')){
            $request->validate([
                'name'=>'required',
                'brandId'=>'required',
            ],[
                'name.required'=> 'Proporciona el nombre',
                'brandId'=>'Selecciona la marca',
            ]);
            $modelo=new Modelo();
            $modelo->name = $request->input('name');
            $modelo->brandId = $request->input('brandId');
            $modelo->save();
            return response()->json(['success' => true, 'message' => 'Modelo creado correctamente']);
        }
    }

    public function update(Request $request, $id){
        $validated= $request->validate([
            'name'=> 'sometimes|required|string|max:40',
            'branId'=> 'nullable|integer',
        ],[
            'name.required'=>'Indique el nuevo dato',
            'name.string'=> 'El modelo debe sre una cadena de text',
            'name.max'=>'El nombre debe tener maximo 40 caracteres.',
        ]);

        $modelo =Modelo::find($id);


        if(!$modelo){
            return response()->json([
                'succes'=> false,
                'message'=> 'Modelo no existente'
            ], 404);
        }
        if ($request->has('name')) {
            $modelo->name = $request->input('name');
        }

        if ($request->has('brandId')) {
            $modelo->brandId = $request->input('brandId');
        }
        $modelo->save();

        return response()->json([
            'success' => true,
            'message' => 'Modelo actualizado.',
            'data' => $modelo
        ]);

    }
}
