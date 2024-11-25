<?php

namespace App\Http\Controllers;
use App\Models\Mbctque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ShopFloorProdController extends Controller{

    //obtiene los datos generales  para la tabla
    public function info(Request $request){

        $vData = $this->validateDate($request);

        $startDate = $vData['start_date'];
        $endDate = $vData['end_date'];
        $startTime = $vData['start_time'] ?? '00:00:00';
        $endTime = $vData['end_time'] ?? '23:59:59';

        $startDateTime = "$startDate $startTime";
        $endDateTime = "$endDate $endTime";

        $tableName = (new Mbctque)->getTable();

        $result = DB::table($tableName)
        ->selectRaw(
             'SERIAL_NUMBER
            ,BUILD_CODE
            ,CREATE_TS
            ,ShipSerial
            ,ShipLabelTimeStamp'
        )
        ->whereBetween('CREATE_TS', [$startDateTime, $endDateTime])
        ->where(function ($query) {
            $query->where(function ($subQuery) {
                $subQuery->whereRaw('SUBSTRING(BUILD_CODE, 1, 2) = ?', ['9P'])
                         ->whereRaw('SUBSTRING(BUILD_CODE, 18, 1) = ?', ['C'])
                         ->whereNotNull('ShipSerial');
            })
            ->orWhere(function ($subQuery) {
                $subQuery->whereRaw('SUBSTRING(BUILD_CODE, 1, 2) = ?', ['9D'])
                         ->whereRaw('SUBSTRING(BUILD_CODE, 18, 1) = ?', ['T']);
                         //->where('COMMS_STATUS_ID', 50);
            })
            ->orWhere(function ($subQuery) {
                $subQuery->whereRaw('SUBSTRING(BUILD_CODE, 1, 2) = ?', ['9B'])
                         ->whereRaw('SUBSTRING(BUILD_CODE, 18, 1) = ?', ['T']);
                         //->where('COMMS_STATUS_ID', 50);
            });
        })
        //->where('ShipLabelTimeStamp', '>', '2024-09-04')
        //->where(DB::raw("SUBSTRING(build_code,1,2)"), '=', '9P')
        //->where('ShipSerial', '!=', 'null')
        //->where(DB::raw("SUBSTRING(build_code,18,1)"), '=', 'C')
        //->where(DB::raw("SUBSTRING(build_code,4,1)"), '=', 'X')
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
    public function totales(Request $request){
        $vData = $this->validateDate($request);

        $startDate = $vData['start_date'];
        $endDate = $vData['end_date'];
        $startTime = $vData['start_time'] ?? '00:00:00';
        $endTime = $vData['end_time'] ?? '23:59:59';

        $startDateTime = "$startDate $startTime";
        $endDateTime = "$endDate $endTime";
        $tableName = (new Mbctque)->getTable();

        $results = DB::table($tableName)
        ->selectRaw('SUBSTRING(BUILD_CODE, 1, 2) AS Categoria, COUNT(*) as Total')
            ->whereBetween('CREATE_TS', [$startDateTime, $endDateTime])
            ->whereRaw('SUBSTRING(BUILD_CODE,1,2) IN (?, ?, ?)', ['9P', '9D', 'OS'])
            ->where(function($query){
                $query->where(function($subQuery){
                    $subQuery->whereRaw('SUBSTRING(BUILD_CODE,1,2) = ?', ['9P'])
                             ->whereRaw('SUBSTRING(BUILD_CODE,18,1) = ?', ['C'] )
                             ->whereNotNull('ShipSerial');
                })
                ->orWhere(function ($subQuery) {
                    $subQuery->whereRaw('SUBSTRING(BUILD_CODE, 1, 2) = ?', ['9D'])
                             ->whereRaw('SUBSTRING(BUILD_CODE, 18, 1) = ?', ['T']);
                             //->where('COMMS_STATUS_ID', 50); //
                });
            })

            //->whereIn(DB::Raw("SUBSTRING(BUILD_CODE,18,1)"), ['C', 'T', 'B'])
            ->groupBy(DB::raw('SUBSTRING(BUILD_CODE, 1, 2)'))
            ->orderBy('Categoria')
            ->get();

        return response()-> json(["data"=> $results], 200);
    }

    public function validateDate(Request $request){
        $vData = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i:s',
            'end_time' => 'nullable|date_format:H:i:s',
        ]);
        return $vData;
    }


     //SW prox R&B en un mes
     public function mProx(Request $request){
        $vData = $this->validateDate($request);

        $startDate = $vData['start_date'];
        $endDate = $vData['end_date'];
        $startTime = $vData['start_time'] ?? '00:00:00';
        $endTime = $vData['end_time'] ?? '23:59:59';

        $startDateTime = "$startDate $startTime";
        $endDateTime = "$endDate $endTime";



        $result = Mbctque::select(
            DB::raw('CONVERT(DATE, ShipLabelTimeStamp,112) as dia '),
            DB::raw('SUBSTRING(BUILD_CODE, 4, 1) as Color'),
            DB::raw('COUNT(ShipLabelTimeStamp) as Total
          '))
       ->whereBetween('ShipLabelTimeStamp', [$startDateTime, $endDateTime])
       //->where('ShipLabelTimeStamp', '>', '2024-09-04')
       ->where(DB::raw("SUBSTRING(build_code,1,2)"), '=', '9P')
       //->where('ShipSerial', '!=', 'null')
       //->where(DB::raw("SUBSTRING(build_code,18,1)"), '=', 'C')
       //->where(DB::raw("SUBSTRING(build_code,4,1)"), '=', 'X')
       ->groupBy(DB::raw('CONVERT(DATE, ShipLabelTimeStamp,112)'),DB::raw('SUBSTRING(BUILD_CODE, 4, 1)'))
       ->orderBy('dia', 'asc')
       //->distinct()
       ->get();

       return response()-> json(["data"=> $result], 200);

    }

    //SW prox R&B dia actual *
    public function dProx(){
        $currentDate = Carbon::today()->format('Y-m-d');

        $result = Mbctque::selectRaw('
          SUBSTRING(BUILD_CODE, 4, 1) as Color,
          COUNT(*) as Total')
       //->whereBetween('CREATE_TS', [$startDateTime, $endDateTime])
       ->where('ShipLabelTimeStamp', '=',  $currentDate)
       ->where(DB::raw("SUBSTRING(build_code,1,2)"), '=', '9P')
       //->where('ShipSerial', '!=', 'null')
       //->where(DB::raw("SUBSTRING(build_code,18,1)"), '=', 'C')
       //->where(DB::raw("SUBSTRING(build_code,4,1)"), '=', 'X')
       ->groupBy(DB::raw('SUBSTRING(BUILD_CODE, 4, 1)'))
       ->orderBy('Color', 'desc')
       ->distinct()
       ->get();
       return response()-> json(["data"=> $result], 200);

    }

    //SW filtros alex
    public function tacoma(Request $request){
        $vData = $this->validateDate($request);

        $startDate = $vData['start_date'];
        $endDate = $vData['end_date'];
        $startTime = $vData['start_time'] ?? '00:00:00';
        $endTime = $vData['end_time'] ?? '23:59:59';

        $startDateTime = "$startDate $startTime";
        $endDateTime = "$endDate $endTime";
        $tableName = (new Mbctque)->getTable();

        $result=Mbctque::selectRaw("
        BUILD_CODE,
        COUNT(*) AS count_total,
        COUNT(*) * 100.0 / NULLIF(
            (SELECT COUNT(*) FROM {$tableName}
             WHERE CREATE_TS BETWEEN ? AND ?

             AND (
             (SUBSTRING(BUILD_CODE, 3, 1) = 'C' AND SUBSTRING(BUILD_CODE, 9, 1) = '2' AND SUBSTRING(BUILD_CODE, 6, 1) IN ('M', 'B', 'X')
             AND SUBSTRING(BUILD_CODE, 13, 1) = 'C' AND SUBSTRING(BUILD_CODE, 14, 1) = '-' AND SUBSTRING(BUILD_CODE, 10, 1) = '-')

             OR (SUBSTRING(BUILD_CODE, 3, 1) = 'C' AND SUBSTRING(BUILD_CODE, 9, 1) = '2' AND SUBSTRING(BUILD_CODE, 6, 1) IN ('M', 'B', 'X')
              AND SUBSTRING(BUILD_CODE, 13, 1) = 'C' AND SUBSTRING(BUILD_CODE, 14, 1) = '-' AND SUBSTRING(BUILD_CODE, 10, 1) = '3')

              OR (SUBSTRING(BUILD_CODE, 3, 1) = 'C' AND SUBSTRING(BUILD_CODE, 9, 1) = '2' AND SUBSTRING(BUILD_CODE, 6, 1) IN ('M', 'B', 'X')
              AND SUBSTRING(BUILD_CODE, 13, 1) = 'C' AND SUBSTRING(BUILD_CODE, 14, 1) = '-' AND SUBSTRING(BUILD_CODE, 10, 1) = '-'
              AND SUBSTRING(BUILD_CODE, 18, 1) = 'B')
             )),
            0
        ) AS percent_total
        ", [$startDateTime, $endDateTime])
        ->whereBetween('CREATE_TS', [$startDateTime, $endDateTime])
        ->where(function ($query) {
            $query->whereRaw("
        (SUBSTRING(BUILD_CODE, 3, 1) = 'C' AND SUBSTRING(BUILD_CODE, 9, 1) = '2'
        AND SUBSTRING(BUILD_CODE, 6, 1) IN ('M', 'B', 'X')
        AND SUBSTRING(BUILD_CODE, 13, 1) = 'C' AND SUBSTRING(BUILD_CODE, 14, 1) = '-'
        AND SUBSTRING(BUILD_CODE, 10, 1) = '-')

        OR

        (SUBSTRING(BUILD_CODE, 3, 1) = 'C' AND SUBSTRING(BUILD_CODE, 9, 1) = '2'
        AND SUBSTRING(BUILD_CODE, 6, 1) IN ('M', 'B', 'X')
        AND SUBSTRING(BUILD_CODE, 13, 1) = 'C' AND SUBSTRING(BUILD_CODE, 14, 1) = '-'
        AND SUBSTRING(BUILD_CODE, 10, 1) = '3')

        OR

        (SUBSTRING(BUILD_CODE, 3, 1) = 'C' AND SUBSTRING(BUILD_CODE, 9, 1) = '2'
        AND SUBSTRING(BUILD_CODE, 6, 1) IN ('M', 'B', 'X')
        AND SUBSTRING(BUILD_CODE, 13, 1) = 'C' AND SUBSTRING(BUILD_CODE, 14, 1) = '-'
        AND SUBSTRING(BUILD_CODE, 10, 1) = '-')
        ");
        })
        ->groupBy('BUILD_CODE')
        ->get();
        return response()-> json(["data"=> $result], 200);
    }
    //SW total proyeccion
    public function totalesProyeccion(Request $request){
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


    public function filterInfo($year=null){
        $result = (is_null($year) || $year=="")?
        Mbctque::all() :
        Mbctque::where('ShipLabelTimeStamp', $year)->get();

    return response()->json(["data"=>$result]);
    }
    /*
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
    }*/





}
