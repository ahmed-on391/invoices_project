<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceArchiveController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\InvoicesAttachmentsController;

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\UserController;
use App\Models\InvoiceArchive;
use Illuminate\Support\Facades\Route;

use function Pest\Laravel\delete;

Route::get('/', function () {
    return view('auth.login');
});


Route::get('/home', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';

Route::resource('invoices', InvoicesController::class);

Route::resource('sections', SectionsController::class);

Route::resource('products', ProductsController::class);


Route::get('/section/{id}', [InvoicesController::class, 'getproducts']);

// Route::resource('InvoiceAttachments', 'InvoiceAttachmentsController');

Route::resource('InvoiceAttachments', InvoicesAttachmentsController::class);

                            // جزئية تفاصيل الفاتورة
                            //ركز كويس

Route::get('/InvoicesDetails/{id}', [InvoicesDetailsController::class, 'edit']);  //ده للتحرير

Route::get('View_file/{invoice_number}/{file_name}', [InvoicesDetailsController::class, 'open_file']); //ده لفتح ملف بتاع الصورة


Route::get('download/{invoice_number}/{file_name}', [InvoicesDetailsController::class, 'get_file']); //ده لحصول ملف بتاع الصورة



Route::post('delete_file', [InvoicesDetailsController::class, 'destroy'])->name('delete_file'); //ده لمسح ملف بتاع الصورة


Route::get('/edit_invoice/{id}', [InvoicesController::class, 'edit']); 

Route::get('/Status_show/{id}', [InvoicesController::class, 'show'])->name('Status_show'); 

Route::post('/Status_Update/{id}', [InvoicesController::class, 'Status_Update'])->name('Status_Update'); 


//---------------------------------------------------------------------------------------------------------------------------------
                                                // ده عشاااااااااااااان الارشيف 


 Route::resource('Archive', InvoiceArchiveController::class);


Route::get('Invoice_Paid', [InvoicesController::class, 'Invoice_Paid'])->name('Invoice_Paid'); // ده دفع


Route::get('Invoice_Unpaid', [InvoicesController::class, 'Invoice_Unpaid'])->name('Invoice_Unpaid'); //ده الفير المدفوع


Route::get('Invoice_Partial', [InvoicesController::class, 'Invoice_Partial'])->name('Invoice_Partial'); // ده المدفوعة جزئيا


Route::get('print_invoice/{id}', [InvoicesController::class, 'print_invoice']);


//--------------------------------------------------------------------------------------------------------------------------------------

    Route::middleware('auth')->group(function () {
        
        Route::resource('roles', RoleController::class);
    
        Route::resource('users', UserController::class);
    });
    




                            //عشان يتشغل الصفحة اللي هي الادمن الكنترول لابد الاستيراد
                            //كمان عشان يتشغل التسجيل الدخول لازم تخرج الكود برة Auth نهائيا
Route::get('/{page}', [AdminController::class, 'index']);