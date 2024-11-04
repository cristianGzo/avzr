<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function create(Request $request){
        if($request->isMethod('post')){
            $request->validate([
                'name'=>'required',

            ],[
                'name.required'=> 'Proporciona el nombre departamento',
            ]);
            $department=new Department();
            $department->name = $request->input('name');
            $department->save();
            return response()->json(['success' => true, 'message' => 'Department dada de alta']);
        }
    }

    public function update(Request $request, $id){
        $validated= $request->validate([
            'name'=> 'required|string|max:40',
        ],[
            'name.required'=>'Indique el nuevo dato',
            'name.string'=> 'El name debe ser una cadena de text',
        ]);

        $department =Department::find($id);

        if(!$department){
            return response()->json([
                'succes'=> false,
                'message'=> 'Department no existente'
            ], 404);
        }
        $department->name=$request->input('name');
        $department->save();

        return response()->json([
            'success' => true,
            'message' => 'Department actualizado.',
            'data' => $department
        ]);

    }
}
