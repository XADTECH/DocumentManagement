<?php

use App\Http\Controllers\AuthenticateController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\UserController;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ImportExportController;
use App\Http\Controllers\SubcategoryController;

// Main Page Route
// Public route for the login page with middleware to redirect if authenticated
Route::get('/', [LoginBasic::class, 'index'])
    ->name('auth-login-basic')
    ->middleware('redirectIfAuthenticated');

Route::post('/login-user', [AuthenticateController::class, 'loginUser'])->name('login-user');

//check all routes
Route::middleware(['checklogin'])->group(function () {
    Route::get('/checkhash', [AuthenticateController::class, 'checkHash'])->name('checkhash');
    Route::post('/logout', [AuthenticateController::class, 'LogoutUser'])->name('logout');

    Route::get('/dashboard', [Analytics::class, 'index'])->name('dashboard-analytics');

    //Project Budget import exportx
    Route::get('/download-bank-excel', [ImportExportController::class, 'bankexport'])->name('banks.download');
    Route::get('/salary/export/{id}', [ImportExportController::class, 'salaryexport'])->name('sarlary-export');
    Route::post('/salary-import', [ImportExportController::class, 'uploadSalary'])->name('salary.import');
    Route::get('/facilitiescost/export/{id}', [ImportExportController::class, 'facilitycostexport'])->name('facility-export');
    Route::post('/facilities-import', [ImportExportController::class, 'uploadFacilities'])->name('facilities.import');
    Route::get('/materialcost/export/{id}', [ImportExportController::class, 'materialcostexport'])->name('material-export');
    Route::post('/material-import', [ImportExportController::class, 'uploadMaterial'])->name('material.import');
    Route::get('/capitalexpenditure/export/{id}', [ImportExportController::class, 'capitalexpenditureexport'])->name('capitalexpenditure-export');
    Route::post('/capital-import', [ImportExportController::class, 'uploadCapital'])->name('capital.import');
    Route::get('/revenueplan/export/{id}', [ImportExportController::class, 'revenueplanexport'])->name('revenueplan-export');
    Route::post('/revenue-import', [ImportExportController::class, 'uploadRevenue'])->name('revenue.import');

    // authentication
    Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
    Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');

    //user management
    Route::post('add-user', [UserController::class, 'store'])->name('add-user');
    Route::get('/users', [UserController::class, 'usersList'])->name('user-lists');
    Route::get('/add-user', [UserController::class, 'index'])->name('add-user');

    //Department management

    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments-list');
    Route::get('/add-departments', [DepartmentController::class, 'create'])->name('add-departments');

    Route::post('store-department', [DepartmentController::class, 'store'])->name('store-department');

    Route::get('edit-department/{id}', [DepartmentController::class, 'edit'])->name('edit-department');
    Route::post('update-department/{id}', [DepartmentController::class, 'update'])->name('update-department');
    Route::delete('delete-department/{id}', [DepartmentController::class, 'destroy'])->name('delete-department');


    Route::get('/subcategories', [SubcategoryController::class, 'index'])->name('subcategories.index');
Route::get('/add-subcategory', [SubcategoryController::class, 'create'])->name('subcategories.create');
Route::post('/store-subcategory', [SubcategoryController::class, 'store'])->name('subcategories.store');
Route::get('/edit-subcategory/{id}', [SubcategoryController::class, 'edit'])->name('subcategories.edit');
Route::post('/update-subcategory/{id}', [SubcategoryController::class, 'update'])->name('subcategories.update');
Route::delete('/delete-subcategory/{id}', [SubcategoryController::class, 'destroy'])->name('subcategories.destroy');
});
