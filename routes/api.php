
<?php

use App\Http\Controllers\DirectCostController;
use App\Http\Controllers\InDirectCostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlannedCashController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\BusinessUnitController;
use App\Http\Controllers\BusinessClientController;
use App\Http\Controllers\PurcahseOrderController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

//cash flow management routes
Route::post('/add-opening-balance', [PlannedCashController::class, 'storeOpeningBalance']);
Route::get('/get-opening-balance', [PlannedCashController::class, 'getopeningBalance']);
Route::post('/add-cash-plan', [PlannedCashController::class, 'addcashplanAmount']);
Route::post('/add-cash-receive', [PlannedCashController::class, 'addcashreceiveAmount']);

//add bank record
Route::post('/add-bank-record', [BankController::class, 'addRecord'])->name('add-bank-record');


//get all bank records
Route::get('/get-bank-records', [BankController::class, 'getRecords'])->name('get-bank-records');
Route::post('/update-bank-record', [BankController::class, 'updateRecord'])->name('update-bank-record');
Route::post('/delete-bank-record', [BankController::class, 'deleteRecord'])->name('delete-bank-record');

//get all projects routes
Route::post('/add-project', [ProjectController::class, 'addRecord'])->name('add-project');
Route::get('/get-projects', [ProjectController::class, 'getRecords'])->name('get-projects');
Route::post('/update-project', [ProjectController::class, 'updateRecord'])->name('update-project');
Route::post('/delete-project', [ProjectController::class, 'deleteRecord'])->name('delete-project');

//add business unit
Route::post('/add-business-unit', [BusinessUnitController::class, 'store'])->name('add-business-unit');
Route::get('/get-business-units', [BusinessUnitController::class, 'index'])->name('get-business-units');
Route::post('/update-business-unit', [BusinessUnitController::class, 'update'])->name('update-business-unit');
Route::post('/delete-business-unit', [BusinessUnitController::class, 'destroy'])->name('delete-business-unit');

// Routes for Business Clients
Route::get('/business-clients/add', [BusinessClientController::class, 'showaddBusinessClient']);
Route::post('/business-clients', [BusinessClientController::class, 'addRecord']);
Route::get('/business-clients', [BusinessClientController::class, 'getRecords']);
Route::post('/update-business-clients', [BusinessClientController::class, 'updateRecord']);
Route::post('/delete-business-clients', [BusinessClientController::class, 'deleteRecord']);


// Routes for users
Route::get('/get-users', [UserController::class, 'getRecords']);
Route::post('/update-user', [UserController::class, 'updateRecord'])->name('update-user');
Route::post('/delete-user', [UserController::class, 'deleteRecord'])->name('delete-user');

//save purchase order 
Route::post('/save-purchase-order', [PurcahseOrderController::class, 'store'])->name('save-purchase-order');


Route::post('/delete-salary', [DirectCostController::class, 'deleteSalary'])->name('delete-salary');
Route::post('/delete-facilities', [DirectCostController::class, 'deleteFacilities'])->name('delete-facilities');
Route::post('/delete-material', [DirectCostController::class, 'deleteMaterial'])->name('delete-facilities');
Route::post('/delete-costoverhead', [InDirectCostController::class, 'deleteCostOverHead'])->name('delete-cost');
Route::post('/delete-financial', [InDirectCostController::class, 'deleteFinancial'])->name('delete-financial');

Route::post('/delete-capital', [DirectCostController::class, 'deleteCapital'])->name('delete-capital');
