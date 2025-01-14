<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\empleadosController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ShopFloorProdController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\ProductionCategoryController;
use App\Http\Controllers\SalesProjectionController;
use App\Http\Controllers\WeeksController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//Rutas tabla Reportes
Route::get('/report', [empleadosController::class, 'index'])->name("index");
Route::get('/reports/{salary?}', [ShopFloorProdController::class, 'info'])->name("api.reports");
Route::get('/borrar/{salary?}', [ShopFloorProdController::class, 'principalTableServer'])->name("api.reports");

Route::get('/year', [ShopFloorProdController::class, 'year'])->name("api.year");
Route::get('/filter/{year?}', [ShopFloorProdController::class, 'filterInfo'])->name("filter");
Route::get('/total', [ShopFloorProdController::class, 'totales'])->name("total");
Route::get('/dProx', [ShopFloorProdController::class, 'dProx'])->name("dProx");
Route::get('/mProx', [ShopFloorProdController::class, 'mProx'])->name("mProx");
Route::get('/mCreatedProx', [ShopFloorProdController::class, 'mCreateProx'])->name("mCreateProx");
Route::get('/wProx', [ShopFloorProdController::class, 'wProx'])->name("wProx");

Route::get('/tcma', [ShopFloorProdController::class, 'tacoma'])->name("tacoma");


//PARA GRAFICOS DE PROYECCION
Route::get('/getCat', [ProductionCategoryController::class, 'getCategory'])->name("getCat"); //para select
Route::post('/create', [salesProjectionController::class, 'createT'])->name("create");
Route::get('/salesProjection', [salesProjectionController::class, 'getProjection'])->name("salesProjection");
//Route::get('/employees', [empleadosController::class, 'employees'])->name("employees");

Route::get('/week', [WeeksController::class, 'get'])->name("getWeek");

Route::get('/weekTest', [SalesProjectionController::class, 'getTest'])->name("getWeek");



/*Route::get('/report', [empleadosController::class, 'index'])->name("index");
Route::get('/reports/{salary?}', [empleadosController::class, 'reports'])->name("api.reports");
Route::get('/salaries', [empleadosController::class, 'salaries'])->name("api.salaries");
Route::get('/employees', [empleadosController::class, 'employees'])->name("employees");*/



/*/Rutas dispositivos
Route::post('addBrand', [BrandController::class, 'create'])-> name("addBrand");
Route::post('addModelo', [Modelo::class, 'create'])->name("addModelo");
Route::post('addDevice', [device::class, 'create'])->name("addDevice");*/

Route::post('/brand', [BrandController::class, 'create'])-> name("addBrand");
Route::patch('/brand/{id}', [BrandController::class, 'update'])-> name("updateBrand");
Route::post('/modelo', [ModeloController::class, 'create'])->name("addModelo");
Route::patch('/modelo/{id}', [ModeloController::class, 'update'])->name("updateModelo");
Route::post('/addDevice', [device::class, 'create'])->name("addDevice");


//department
Route::post('/department', [DepartmentController::class, 'create'])-> name("addDepartment");
Route::patch('/department/{id}', [DepartmentController::class, 'update'])-> name("updateDepartment");

//employee
Route::post('/employee', [empleadosController::class, 'create'])->name("create");
Route::patch('/employee/{id}', [empleadosController::class, 'update'])->name("update");

//Rol
Route::post('/rol', [RolController::class, 'create'])->name("create");
Route::patch('/rol/{id}', [RolController::class, 'update'])->name("update");

