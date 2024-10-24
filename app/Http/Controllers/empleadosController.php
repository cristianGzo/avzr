<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleados;

class empleadosController extends Controller
{

    public function index(){
        return view('report');
    }

    public function employees(){
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

   /* public function getEmployeesBySalary($salary) {
        $employees = Empleados::where('salario', $salary)->get();
        return response()->json($employees);
    }*/


    public function salaries() {
        $salariesQuery = Empleados::select('salario as text', 'id')->distinct();

        $salaries = $salariesQuery->get();

        return response()->json(["results"=>$salaries]);
        //["result" =>
    }

}
