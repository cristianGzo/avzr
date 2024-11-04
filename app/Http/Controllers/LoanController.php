<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function create(Request $request){
        if($request->isMethod('post')){
            $request->validate([
                'fechaPrestamo'=>'required',
                'empleadoId'=>'required',
                'dispositivoId'=>'required'
            ],[
                'fechaPrestamo.required'=> 'Indica la fecha',
                'empleadoId.required'=> 'Indica el solicitante',
                'dispositivoId'=> 'Indica el dispositvo a prestao'
            ]);
            $loan=new Loan();
            $loan->fechaPrestamo = $request->input('fechaPrestamo');
            $loan->empleadoId = $request->input('empleadoId');
            $loan->dispositivoId = $request->input('dispositivoId');
            $loan->save();
            return response()->json(['success' => true, 'message' => 'Prestamo dado de alta']);
        }
    }

    public function update(Request $request, $id){
        $validated= $request->validate([
            'fechaPrestamo'=> 'required|string|max:40',
            'empleadoId'=> 'required',
            'dispositivoId'=> 'required',

        ],[
            'fechaPrestamo.required'=>'Indique el nuevo dato',
            'empleadoId.required'=>'Indique el nuevo empId',
            'dispositivoId.required'=>'Indique el nuevo deviceId'
        ]);

        $loan =Loan::find($id);


        if(!$loan){
            return response()->json([
                'succes'=> false,
                'message'=> 'loan no existente'
            ], 404);
        }
        $loan->fechaPrestamo=$request->input('fechaPrestamo');
        $loan->empleadoId=$request->input('empleadoId');
        $loan->dispositivoId=$request->input('dispositivoId');
        $loan->save();

        return response()->json([
            'success' => true,
            'message' => 'Loan actualizado.',
            'data' => $loan
        ]);

    }

}
