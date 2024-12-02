<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeeksModel;



class WeeksController extends Controller
{
    //
    public function create(Request $request){
        if($request->isMethod('post')){
            $request->validate([
                'startDate'=>'required',
                'endDate'=>'required',

            ],[
                'startDate.required'=> 'Ingresa inicio de semana',
                'endDate.required'=> 'Ingresa fin de semana',

            ]);
            $week=new ProductionCategory();
            $week->startDate=$request->input('startDate');
            $week->endDate=$request->input('endDate');
            $week->save();
            return response()->json(['success' => true, 'message' => 'Week created']);
        }
    }

    public function get(){
        $result=WeeksModel::selectRaw('CONVERT(DATE, startDate) as startDate, CONVERT(DATE, endDate) as endDate')
        ->get();
        return response()->json(["data"=>$result], 200);
    }

    public function getTest(){
        $results = WeeksModel::with('weeks')->get()->map(function ($total) {
            return [
                'Total' => $total->total,
                'Semana' => "{$total->semana->inicio} - {$total->semana->fin}",
            ];
        });

        return response()->json($results);
    }
}
