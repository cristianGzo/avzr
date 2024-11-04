<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleados;

class empleadosController extends Controller
{

    public function index(){
        return view('report');
    }

    public function create(Request $request){
        if($request->isMethod('post')){
            $request->validate([
                'name'=>'required',
                'lastName'=>'required',
                'imagen'=>'required',
                'departmentId'=>'required'
            ],[
                'name.required'=> 'Proporciona el nombre',
                'lastName.required'=> 'Proporciona tu apellido',
            ]);
            $brand=new Empleados();
            $brand->name = $request->input('name');
            $brand->lastName = $request->input('lastName');
            $brand->imagen = $request->input('imagen');
            $brand->departmentId = $request->input('departmentId');
            $brand->save();
            return response()->json(['success' => true, 'message' => 'Empleado dado de alta']);
        }
    }

    public function update(Request $request, $id){
        $validated= $request->validate([
            'nombre'=> 'required|string|max:40',
            'apellidos'=> 'required|string|max:40',

        ],[
            'name.required'=>'Indique el nuedo dato',
            'apellidos.string'=> 'El nombre debe sre una cadena de text',
        ]);

        $empleados =Empleados::find($id);


        if(!$empleados){
            return response()->json([
                'succes'=> false,
                'message'=> 'Empleado no existente'
            ], 404);
        }
        $empleados->name=$request->input('nombre');
        $empleados->name=$request->input('apellidos');
        $empleados->save();

        return response()->json([
            'success' => true,
            'message' => 'Empleado actualizado.',
            'data' => $empleados
        ]);

    }

    /*public function employees(){
        $employees = Empleados::all();
        //dd($employees);

        if($employees-> isEmpty()){
            $data=[
                'message' => 'Sin data',
                'status ' => 404
            ];
            return response()->json($data, 404);
        }
        return response()-> json(["data"=> $employees], 200);
    }

    public function reports($salary){
        $employees = (is_null($salary) || $salary=="")?
        Empleados::all() :
        Empleados::where('Salario', $salary)->get();

    return response()->json(["data"=>$employees]);
    }

    public function salaries() {
        $salariesQuery = Empleados::select('salario as text', 'id')->distinct();

        $salaries = $salariesQuery->get();

        return response()->json(["results"=>$salaries]);
        //["result" =>
    }*/

}
