<?php

namespace App\Http\Controllers;
use App\Models\Mbctque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopFloorProdController extends Controller
{

    //obtiene los datos generales  para la tabla
    public function info(){
        $result = Mbctque::select([
             'SERIAL_NUMBER'
            ,'BUILD_CODE'
            ,'CREATE_TS'
            ,'ShipSerial'
            ,'ShipLabelTimeStamp'
        ])
        //->where('ShipLabelTimeStamp', '>', '2024-09-04')
        ->where(DB::raw("SUBSTRING(build_code,1,2)"), '=', '9P')
        ->where('ShipSerial', '!=', 'null')
        ->where(DB::raw("SUBSTRING(build_code,18,1)"), '=', 'C')
        ->where(DB::raw("SUBSTRING(build_code,4,1)"), '=', 'X')
        ->orderBy('ShipLabelTimeStamp', 'desc')
        ->distinct()
        ->get();
        //dd($result);

        if($result-> isEmpty()){
            $data=[
                'message' => 'Sin data',
                'status ' => 404
            ];
            return response()->json($data, 404);
        }
        return response()-> json(["data"=> $result], 200);
    }
    //obtiene total por Dcab, total, etc.
    public function totales(){
    $results = Mbctque::selectRaw('SUBSTRING(BUILD_CODE, 1, 2) as Categoria, COUNT(*) as Total')
        ->groupBy(DB::raw('SUBSTRING(BUILD_CODE, 1, 2)'))
        ->orderBy('Categoria')
        ->get();

    return response()->json(["data"=> $results], 200);
    }

    public function filterInfo($year=null){
        $result = (is_null($year) || $year=="")?
        Mbctque::all() :
        Mbctque::where('ShipLabelTimeStamp', $year)->get();

    return response()->json(["data"=>$result]);
    }

    public function year() {
        $result = DB::table('SHOPFLOOR_PRODUCT_INSTANCE')->distinct()
        ->select('SHOPFLOOR_PRODUCT_INSTANCE_ID as id', 'ShipLabelTimeStamp as text')
        //->where('ShipLabelTimeStamp', '>', '2024-09-04')
        ->where(DB::raw("SUBSTRING(build_code,1,2)"), '=', '9P')
        ->where('ShipSerial', '!=', 'null')
        ->where(DB::raw("SUBSTRING(build_code,18,1)"), '=', 'C')
        ->where(DB::raw("SUBSTRING(build_code,4,1)"), '=', 'X')
        ->orderBy('ShipLabelTimeStamp', 'desc')
        ->distinct()
        ->get();

        if($result-> isEmpty()){
            $data=[
                'message' => 'Sin data',
                'status ' => 404
            ];
            return response()->json($data, 404);
        }
        return response()-> json(["data"=> $result], 200);
    }



}
