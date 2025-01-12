<?php

use App\Http\Controllers\AuthenticateController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\UserController;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ImportExportController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\DocumentTypeController;

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

    // Document Routes
    Route::get('/list-documents', [DocumentController::class, 'myDocuments'])->name('documents.myDocuments'); // List all documents
    Route::get('/add-document', [DocumentController::class, 'create'])->name('documents.create'); // Show create form
    Route::post('/store-document', [DocumentController::class, 'store'])->name('documents.store'); // Store new document
    Route::get('/edit-document/{id}', [DocumentController::class, 'edit'])->name('documents.edit'); // Show edit form
    Route::post('/update-document/{id}', [DocumentController::class, 'update'])->name('documents.update'); // Update document
    Route::delete('/delete-document/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy'); // Delete document
    Route::get('/departments/{id}/subcategories', [DocumentController::class, 'showSubcategories'])->name('departments.subcategories');
    Route::get('/subcategories/{id}/documents', [DocumentController::class, 'showDocuments'])->name('subcategories.documents');
    Route::get('/subcategories/{subcategory}/document-types', [DocumentController::class, 'showDocumentTypes'])->name('subcategories.document-types');
    Route::get('/document-types/{documentType}/documents', [DocumentController::class, 'showDocuments'])->name('document-types.documents');
    Route::get('/get-subcategories/{department_id}', [DocumentController::class, 'getSubcategories'])->name('get.subcategories');
    Route::get('/get-document-types/{subcategory_id}', [DocumentController::class, 'getDocumentTypes'])->name('get.document.types');
    Route::get('/pending-documents', [DocumentController::class, 'documentPending'])->name('documents.pending');
    Route::get('/approved-documents', [DocumentController::class, 'documentApproved'])->name('documents.approved');
    Route::get('/rejected-documents', [DocumentController::class, 'documentRejected'])->name('documents.rejected');
    Route::get('/documents/{id}', [DocumentController::class, 'show'])->name('documents.show');
    Route::post('/documents/update-status', [DocumentController::class, 'updateStatus'])->name('documents.updateStatus');
    Route::post('/documents/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::get('/documents/view/{id}', [DocumentController::class, 'view'])->name('documents.view');

    Route::prefix('document-types')->group(function () {
        Route::get('/', [DocumentTypeController::class, 'index'])->name('document-types.index');
        Route::get('/add', [DocumentTypeController::class, 'create'])->name('document-types.create');
        Route::post('/store', [DocumentTypeController::class, 'store'])->name('document-types.store');
        Route::get('/edit/{id}', [DocumentTypeController::class, 'edit'])->name('document-types.edit');
        Route::put('/update/{id}', [DocumentTypeController::class, 'update'])->name('document-types.update');
        Route::delete('/delete/{id}', [DocumentTypeController::class, 'destroy'])->name('document-types.destroy');
    });
});
