<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DevolucionController extends Controller
{
    public function create(Request $request){
        if($request->isMethod('post')){
            $request->validate([
                'fecha'=>'required',
                'estado'=>'required|string',
                'observacion'=>'nullable|string',
                'prestamoId'=>'required'
            ],[
                'fecha.required'=> 'Proporciona la fecha',
                'estado.required'=> 'Proporciona estado',
                'prestamoId.required'=> 'Indica el prestamo correspondiente',

            ]);
            $devolucion=new Devolucion();
            $devolucion->fecha = $request->input('fecha');
            $devolucion->estado = $request->input('estado');
            $devolucion->observacion = $request->input('observacion');
            $devolucion->prestamoId = $request->input('prestamoId');
            $devolucion->save();
            return response()->json(['success' => true, 'message' => 'Devolucion registrada']);
        }
    }

    public function update(Request $request, $id){
        $validated= $request->validate([
            'fecha'=> 'required',
            'estado'=> 'string|max:40',
            'observacion'=> 'string|max:200',

        ],[
            'fecha.required'=>'Indique la fecha',

        ]);

        $devolucion =Devolucion::find($id);

        if(!$devolucion){
            return response()->json([
                'succes'=> false,
                'message'=> 'Devolucion no existente'
            ], 404);
        }
        $devolucion->fecha=$request->input('fecha');
        $devolucion->estado=$request->input('estado');
        $devolucion->observacion=$request->input('observacion');
        $devolucin->prestamoId=$request->input('prestamoId');
        $devolucion->save();

        return response()->json([
            'success' => true,
            'message' => 'Devolucion actualizado.',
            'data' => $empleados
        ]);

    }

}
