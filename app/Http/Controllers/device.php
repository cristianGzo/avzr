<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class device extends Controller
{
    //
    public function create(Request $request){
        if($request->isMethod('post')){
            $request->validate([
                'noSerie'=>'required',
                'descripcion'=>'required',
                'stock'=>'required',
                'estado'=>'required',
                'qr'=>'qr',
                'idModelo'=>'required',
                'idCategoria'=>'required'

            ],[
                'noSerie.required'=> 'Proporciona el nombre'
            ]);
            $device=new Device();
            $device->noSerie = $request->input('noSerie');
            $device->descripcion = $request->input('descripcion');
            $device->stock = $request->input('stock');
            $device->estado = $request->input('estado');
            $device->qr = $request->input('qr');
            $device->idModelo = $request->input('idModelo');
            $device->idCategoria = $request->input('idCategoria');
            $device->save();
            return response()->json(['success' => true, 'message' => 'Device creada correctamente']);
        }
    }

    public function update(Request $request, $id){
        $validated= $request->validate([
            'noSerie'=> 'nullable|string|max:40',
            'descripcion'=> 'nullable|string|max:40',
            'stock'=> 'nullable|string|max:40',
            'estado'=> 'nullable|string|max:40',
            'qr'=> 'nullable|string|max:40',
            'idCategoria'=> 'nullable|string|max:40',
            'idModelo'=> 'nullable|string|max:40',
        ],[
            'noSerie.required'=>'Indique el nuevo dato',
            'noSerie.string'=> 'El nombre debe sre una cadena de text',
            'noSerie.max'=>'El nombre debe tener maximo 40 caracteres.',
        ]);

        $device =Device::find($id);

        if(!$device){
            return response()->json([
                'succes'=> false,
                'message'=> 'Marca no existente'
            ], 404);
        }
        $device->noSerie=$request->input('noSerie');
        $device->descripcion=$request->input('descripcion');
        $device->stock=$request->input('stock');
        $device->estado=$request->input('estado');
        $device->qr=$request->input('qr');
        $device->idCategoria=$request->input('idCategoria');
        $device->idModelo=$request->input('idModelo');
        $device->save();

        return response()->json([
            'success' => true,
            'message' => 'DEVICE actualizada.',
            'data' => $device
        ]);

    }
}
