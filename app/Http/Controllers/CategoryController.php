<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function create(Request $request){
        if($request->isMethod('post')){
            $request->validate([
                'nombre'=>'required',

            ],[
                'nombre.required'=> 'Proporciona el nombre categoria',
            ]);
            $categoria=new Category();
            $categoria->nombre = $request->input('nombre');
            $categoria->save();
            return response()->json(['success' => true, 'message' => 'Categoria dada de alta']);
        }
    }

    public function update(Request $request, $id){
        $validated= $request->validate([
            'nombre'=> 'required|string|max:40',
        ],[
            'nombre.required'=>'Indique el nuevo dato',
            'nombre.string'=> 'El nombre debe ser una cadena de text',
        ]);

        $category =Category::find($id);

        if(!$category){
            return response()->json([
                'succes'=> false,
                'message'=> 'Categoria no existente'
            ], 404);
        }
        $category->nombre=$request->input('nombre');
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Categoria actualizado.',
            'data' => $category
        ]);

    }

}
