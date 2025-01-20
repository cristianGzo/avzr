<?php

namespace App\Http\Controllers;
use App\Models\Mbctque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Illuminate\Support\Facades\Log;

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
            ->whereRaw('SUBSTRING(BUILD_CODE,1,2) IN (?, ?, ?, ?)', ['9P', '9D', 'OS', '9B'])
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
                })
                ->orWhere(function ($subQuery) {
                    $subQuery->whereRaw('SUBSTRING(BUILD_CODE, 1, 2) = ?', ['OS'])
                             ->whereRaw('SUBSTRING(BUILD_CODE, 18, 1) = ?', ['C']);
                             //->where('COMMS_STATUS_ID', 50); //
                })
                ->orWhere(function ($subQuery) {
                    $subQuery->whereRaw('SUBSTRING(BUILD_CODE, 1, 2) = ?', ['9B'])
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
    public function proyeccion(Request $request){


        $vData = $this->validateDate($request);

        $startDate = $vData['start_date'];
        $endDate = $vData['end_date'];
        $startTime = $vData['start_time'] ?? '00:00:00';
        $endTime = $vData['end_time'] ?? '23:59:59';

        $startDateTime = "$startDate $startTime";
        $endDateTime = "$endDate $endTime";
        $tableName = (new Mbctque)->getTable();

        $results = DB::table($tableName)
        ->selectRaw("
            CASE
                WHEN SUBSTRING(BUILD_CODE, 1, 2) IN ('9P', '9D', '9B') THEN 'Seats'
                WHEN SUBSTRING(BUILD_CODE, 1, 2) = 'OS' THEN 'OHS'

                ELSE SUBSTRING(BUILD_CODE, 1, 2)
            END AS Categoria,
            COUNT(*) as Total
        ")
        ->whereBetween('CREATE_TS', [$startDateTime, $endDateTime])
        ->where(function ($query) {
                $query->where(function($subQuery){
                    $subQuery->whereRaw('SUBSTRING(BUILD_CODE,1,2) = ?', ['9P'])
                             ->whereRaw('SUBSTRING(BUILD_CODE,18,1) = ?', ['C'] )
                             ->whereNotNull('ShipSerial');
                })
                ->orWhere(function ($subQuery) {
                    $subQuery->whereRaw('SUBSTRING(BUILD_CODE, 1, 2) = ?', ['9D'])
                             ->whereRaw('SUBSTRING(BUILD_CODE, 18, 1) = ?', ['T']);
                             //->where('COMMS_STATUS_ID', 50); //
                })
                ->orWhere(function ($subQuery) {
                    $subQuery->whereRaw('SUBSTRING(BUILD_CODE, 1, 2) = ?', ['OS'])
                             ->whereRaw('SUBSTRING(BUILD_CODE, 18, 1) = ?', ['C']);
                             //->where('COMMS_STATUS_ID', 50); //
                })
                ->orWhere(function ($subQuery) {
                    $subQuery->whereRaw('SUBSTRING(BUILD_CODE, 1, 2) = ?', ['9B'])
                             ->whereRaw('SUBSTRING(BUILD_CODE, 18, 1) = ?', ['T']);
                             //->where('COMMS_STATUS_ID', 50); //
                });

            })

            //->whereIn(DB::Raw("SUBSTRING(BUILD_CODE,18,1)"), ['C', 'T', 'B'])
            ->groupBy(DB::raw("
            CASE
                WHEN SUBSTRING(BUILD_CODE, 1, 2) IN ('9P', '9D', '9B') THEN 'Seats'
                WHEN SUBSTRING(BUILD_CODE, 1, 2) = 'OS' THEN 'OHS'
                ELSE SUBSTRING(BUILD_CODE, 1, 2)
            END
        "))
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
    //SW prox R&B creados en un mes
    public function mCreateProx(Request $request){
        $vData = $this->validateDate($request);

        $startDate = $vData['start_date'];
        $endDate = $vData['end_date'];
        $startTime = $vData['start_time'] ?? '00:00:00';
        $endTime = $vData['end_time'] ?? '23:59:59';

        $startDateTime = "$startDate $startTime";
        $endDateTime = "$endDate $endTime";



        $result = Mbctque::select(
            DB::raw('CONVERT(DATE, CREATE_TS,112) as dia '),
            DB::raw('SUBSTRING(BUILD_CODE, 4, 1) as Color'),
            DB::raw('COUNT(ShipLabelTimeStamp) as Total
          '))
       ->whereBetween('CREATE_TS', [$startDateTime, $endDateTime])
       ->where(DB::raw("SUBSTRING(build_code,1,2)"), '=', '9P')
       //->where('ShipSerial', '!=', 'null')
       ->where(DB::raw("SUBSTRING(build_code,18,1)"), '=', 'C')
       //->where(DB::raw("SUBSTRING(build_code,4,1)"), '=', 'X')
       ->groupBy(DB::raw('CONVERT(DATE, CREATE_TS,112)'),DB::raw('SUBSTRING(BUILD_CODE, 4, 1)'))
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

    //current week prox
    public function wProx(){
        $startWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endWeek= Carbon::now();

        $result = Mbctque::selectRaw('
          SUBSTRING(BUILD_CODE, 4, 1) as Color,
          COUNT(*) as Total')
       //->whereBetween('CREATE_TS', [$startDateTime, $endDateTime])
       ->whereBetween('ShipLabelTimeStamp',  [$startWeek, $endWeek])
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



    public function principalTableServer(Request $request){

        $vData = $this->validateDate($request);

        $startDate = $vData['start_date'];
        $endDate = $vData['end_date'];
        $startTime = $vData['start_time'] ?? '00:00:00';
        $endTime = $vData['end_time'] ?? '23:59:59';
        $filter1and2 = $request->input('filter1and2', null);
        $filterPos3 = $request->input('filterPos3', null);
        $filterPos7 = $request->input('filterPos7', null);
        $filterPos9= $request->input('filterPos9', null);
        $filterPos13= $request->input('filterPos13', null);
        $filterPos10= $request->input('filterPos10', null);
        $filterPos18= $request->input('filterPos18', null);

        $startDateTime = "$startDate $startTime";
        $endDateTime = "$endDate $endTime";

        $tableName = (new Mbctque)->getTable();


        $totalWithoutFilters = DB::table($tableName)
    ->where(function ($query) use ($startDateTime, $endDateTime) {
        $query->where(function ($subQuery) use ($startDateTime, $endDateTime) {
            $subQuery->whereRaw('SUBSTRING(BUILD_CODE, 1, 2) = ?', ['9P'])
                     ->whereRaw('SUBSTRING(BUILD_CODE, 18, 1) = ?', ['C'])
                     ->whereBetween('ShipLabelTimeStamp', [$startDateTime, $endDateTime]);
        })
        ->orWhere(function ($subQuery) use ($startDateTime, $endDateTime) {
            $subQuery->whereRaw('SUBSTRING(BUILD_CODE, 1, 2) IN (?, ?)', ['9D', '9B'])
                     ->whereRaw('SUBSTRING(BUILD_CODE, 18, 1) = ?', ['T'])
                     ->whereBetween('CREATE_TS', [$startDateTime, $endDateTime]);
        });
    })
    ->count();



        $result = DB::table($tableName)
        ->selectRaw(
             'SERIAL_NUMBER
            ,BUILD_CODE
            ,CREATE_TS
            ,ShipSerial
            ,ShipLabelTimeStamp'
        )

         ->where(function ($query) use ($startDateTime, $endDateTime) {
            $query->where(function ($subQuery) use ($startDateTime, $endDateTime) {
                $subQuery->whereRaw('SUBSTRING(BUILD_CODE, 1, 2) = ?', ['9P'])
                    ->whereBetween('ShipLabelTimeStamp', [$startDateTime, $endDateTime]);
            })
            ->orWhere(function ($subQuery) use ($startDateTime, $endDateTime) {
                $subQuery->whereRaw('SUBSTRING(BUILD_CODE, 1, 2) != ?', ['9P'])
                    ->whereBetween('CREATE_TS', [$startDateTime, $endDateTime]);
            });
        });

        if ($filter1and2 || $filterPos3 || $filterPos7 || $filterPos9 || $filterPos13 || $filterPos10 || $filterPos18) {
            $result->where(function ($subQuery) use ($filter1and2, $filterPos3, $filterPos7 ,$filterPos9, $filterPos13 , $filterPos10, $filterPos18) {
                if ($filter1and2) {
                    $subQuery->where(function ($filterQuery) use ($filter1and2) {
                        if ($filter1and2 === '9P') {
                            $filterQuery->whereRaw('SUBSTRING(BUILD_CODE, 1, 2) = ?', [$filter1and2])
                                ->whereRaw('SUBSTRING(BUILD_CODE, 18, 1) = ?', ['C'])
                                ->whereNotNull('ShipSerial');
                        } elseif (in_array($filter1and2, ['9B', '9D'])) {
                            $filterQuery->whereRaw('SUBSTRING(BUILD_CODE, 1, 2) = ?', [$filter1and2])
                                ->whereRaw('SUBSTRING(BUILD_CODE, 18, 1) = ?', ['T']);
                        } else {
                            $filterQuery->whereRaw('SUBSTRING(BUILD_CODE, 1, 2) = ?', [$filter1and2]);
                        }
                    });
                }
                if ($filterPos3) {
                    $subQuery->whereRaw('SUBSTRING(BUILD_CODE, 3, 1) = ?', [$filterPos3]);
                }
                if ($filterPos7) {
                    $subQuery->whereRaw('SUBSTRING(BUILD_CODE, 7, 1) = ?', [$filterPos7]);
                }
                if ($filterPos9) {
                    $subQuery->whereRaw('SUBSTRING(BUILD_CODE, 9, 1) = ?', [$filterPos9]);
                }
                if ($filterPos13) {
                    $subQuery->whereRaw('SUBSTRING(BUILD_CODE, 13, 1) = ?', [$filterPos13]);
                }
                if ($filterPos10) {
                    $subQuery->whereRaw('SUBSTRING(BUILD_CODE, 10, 1) = ?', [$filterPos10]);
                }
                if ($filterPos18) {
                    $subQuery->whereRaw('SUBSTRING(BUILD_CODE, 18, 1) = ?', [$filterPos18]);
                }
            });
        } else {


            $result->where(function ($query) {
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
        });
        Log::info('Consulta SQL generada:', ['sql' => $result->toSql()]);
    }

        $results = $result->orderBy('ShipLabelTimeStamp', 'desc')->get();

        /*->orderBy('ShipLabelTimeStamp', 'desc')
        ->distinct()
        ->get();*/
        //dd($result);

        if($results-> isEmpty()){
            $data=[
                'message' => 'Sin data',
                'status ' => 404
            ];
            return response()->json($data, 404);
        }
        return response()-> json([
            'totalWithoutFilters' => $totalWithoutFilters,
            "data"=> $results], 200);
    }


    //Servicios para total proX por hora

    public function horaTotalProX (){
        $currentDate = Carbon::today()->format('Y-m-d');

        $response= Mbctque::selectRaw('
            ShipLabelTimeStamp as Time,
            count (*) as Total
        ')
        ->where('ShipLabelTimeStamp', '=',  $currentDate)
       ->where(DB::raw("SUBSTRING(build_code,1,2)"), '=', '9P')
       //->where('ShipSerial', '!=', 'null')
       //->where(DB::raw("SUBSTRING(build_code,18,1)"), '=', 'C')
       //->where(DB::raw("SUBSTRING(build_code,4,1)"), '=', 'X')
       ->groupBy(DB::raw('ShipLabelTimeStamp'))
       ->orderBy('Total', 'desc')
       ->distinct()
       ->get();
    }




}
