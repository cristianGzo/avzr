<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesProjection;

class salesProjectionController extends Controller
{
    //
    public function create(Request $request){

        if($request->isMethod('post')){
            $request->validate([
                'startDate'=>'required|Date',
                'endDate'=>'required|Date',
                'value'=>'required|integer',
                'productionCategoryId'=>'required|integer',
            ],[
                'startDate.required'=> 'Proporciona fecha inicio',
                'endDate.required'=> 'Proporciona fecha fin',
                'productionCategoryId.required'=> 'Proporciona categoria id',
                'value.required'=>'Proporciona el valor',
            ]);
            $salesProjection=new SalesProjection();
            $salesProjection->startDate=$request->input('startDate');
            $salesProjection->endDate=$request->input('endDate');
            $salesProjection->value=$request->input('value');
            $salesProjection->productionCategoryId=$request->input('productionCategoryId');
            $salesProjection->save();
            return response()->json(['success' => true, 'message' => 'Projection created']);
        }
    }

    public function getProjection(){
        $response=SalesProjection::selectRaw(
            'id, value,
            CONVERT(date, startDate)as startDate,
            CONVERT(date, endDate) as endDate')
            ->get();
            return response()->json(["data"=>$response], 200);
    }

    //obtiene total por Dcab, total, etc.
    public function proyeccionTotal(Request $request){
        $vData = $this->validateDate($request);

        $startDate = $vData['start_date'];
        $endDate = $vData['end_date'];
        $startTime = $vData['start_time'] ?? '00:00:00';
        $endTime = $vData['end_time'] ?? '23:59:59';

        $startDateTime = "$startDate $startTime";
        $endDateTime = "$endDate $endTime";

        $results = Mbctque::selectRaw('SUBSTRING(BUILD_CODE, 1, 2) as Categoria, COUNT(*) as Total')
            ->whereBetween('CREATE_TS', [$startDateTime, $endDateTime])
            ->whereIn(DB::Raw("SUBSTRING(BUILD_CODE,18,1)"), ['C', 'T', 'B'])
            ->groupBy(DB::raw('SUBSTRING(BUILD_CODE, 1, 2)'))
            ->orderBy('Categoria')
            ->get();

        return response()-> json(["data"=> $results], 200);
    }
}
