<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
    public function create(Request $request){
        if($request->isMethod('post')){
            $request->validate([
                'name'=>'required',
            ],[
                'name.required'=> 'Proporciona el nombre'
            ]);
            $brand=new Brand();
            $brand->name = $request->input('name');
            $brand->save();
            return response()->json(['success' => true, 'message' => 'Marca creada correctamente']);
        }
    }

    public function update(Request $request, $id){
        $validated= $request->validate([
            'name'=> 'required|string|max:40',
        ],[
            'name.required'=>'Indique el nuevo dato',
            'name.string'=> 'El nombre debe sre una cadena de text',
            'name.max'=>'El nombre debe tener maximo 40 caracteres.',
        ]);

        $brand =Brand::find($id);


        if(!$brand){
            return response()->json([
                'succes'=> false,
                'message'=> 'Marca no existente'
            ], 404);
        }
        $brand->name=$request->input('name');
        $brand->save();

        return response()->json([
            'success' => true,
            'message' => 'Marca actualizada.',
            'data' => $brand
        ]);

    }
}
