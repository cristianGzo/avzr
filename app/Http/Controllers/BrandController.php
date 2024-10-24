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
}
