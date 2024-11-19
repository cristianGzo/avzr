<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductionCategory;

class productionCategoryController extends Controller
{
    //insert Cat como OHS, seats, etc.
    public function create(Request $request){

        if($request->isMethod('post')){
            $request->validate([
                'name'=>'required',
            ],[
                'name.required'=> 'Proporciona el nombre'
            ]);
            $category=new ProductionCategory();
            $category->name=$request->input('name');
            $category->save();
            return response()->json(['success' => true, 'message' => 'Category created']);
        }
    }


    public function getCategory(){
        $result=ProductionCategory::selectRaw('id,
        name')
        ->get();
        return response()->json(["data"=>$result], 200);
    }


}
