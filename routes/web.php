<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NyukinController;
use App\Http\Controllers\CsvImportController;
use App\Http\Controllers\MenuController;

use App\Http\Controllers\AnalysisController;
use Inertia\Inertia;



Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('auth')->group(function () {
    Route::post('/orders/create2', [OrderController::class, 'create2'])->name('orders.create2');
    Route::get('/order_detail/{id}', [OrderController::class, 'detail_edit'])->name('order_detail.edit');
    Route::put('/order_detail/{id}', [OrderController::class, 'detail_update'])->name('order_detail.update');
    Route::get('/order_confirm/{id}', [OrderController::class, 'confirm'])->name('order.confirm');
    Route::get('/seikyu_index', [OrderController::class, 'seikyu_index'])->name('seikyu.index');
    Route::post('/seikyu', [OrderController::class, 'seikyu_store'])->name('seikyu.store');
    Route::get('/seikyu/{id}', [OrderController::class, 'seikyu_show'])->name('seikyu.show');
    Route::get('/pay_create/{id}', [OrderController::class, 'pay_create'])->name('pay.create');
    Route::post('/pay_store', [OrderController::class, 'pay_store'])->name('pay.store');
    Route::get('/pay/{id}', [OrderController::class, 'pay_show'])->name('pay.show');
    Route::get('/pay_edit/{id}', [OrderController::class, 'pay_edit'])->name('pay.edit');
    Route::put('/pay_update/{id}', [OrderController::class, 'pay_update'])->name('pay.update');
    Route::delete('/pay_delete/{id}', [OrderController::class, 'pay_destroy'])->name('pay.destroy');
    Route::get('/my_order_index', [OrderController::class, 'my_order_index'])->name('orders.my_order_index');
    Route::get('/my_order_show/{order}', [OrderController::class, 'my_order_show'])->name('orders.my_order_show');
    Route::post('/items/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::get('/reports/{id}', [ReportController::class, 'index'])->name('reports.index2');
    Route::get('/my_reports/{id}', [ReportController::class, 'my_index'])->name('reports.my_index');
    Route::get('/reports/create/{id}', [ReportController::class, 'create'])->name('reports.create2');
    Route::get('/reports/show/{report}', [ReportController::class, 'show'])->name('reports.show2');
    Route::get('/comments/create/{id}', [CommentController::class, 'comment_create'])->name('comments.create2');
    Route::delete('del_image1', [ReportController::class, 'del_image1'])->name('reports.del_image1');
    Route::delete('del_image2', [ReportController::class, 'del_image2'])->name('reports.del_image2');
    Route::delete('del_image3', [ReportController::class, 'del_image3'])->name('reports.del_image3');
    Route::delete('del_image4', [ReportController::class, 'del_image4'])->name('reports.del_image4');
    Route::get('/orders/{id}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
    Route::get('/order_csv_all', [OrderController::class, 'orderCSV_download_all'])->name('orders.csv_all');
    Route::get('/order_csv', [OrderController::class, 'orderCSV_download'])->name('orders.csv');
    Route::get('/my_order_csv_all', [OrderController::class, 'my_orderCSV_download_all'])->name('orders.my_csv_all');
    Route::get('/my_order_csv', [OrderController::class, 'my_orderCSV_download'])->name('orders.my_csv');
    Route::get('/csv-import', [CsvImportController::class, 'index'])->name('csv.import.index');
    Route::post('/csv-import', [CsvImportController::class, 'store'])->name('csv.import.store');
    Route::get('/csv-progress', [CsvImportController::class, 'progress'])->name('csv.import.progress');

    Route::get('analysis', [AnalysisController::class, 'index'])->name('analysis');
    Route::get('analysis/test', [AnalysisController::class, 'test'])->name('analysis.test');
    Route::get('menu', [MenuController::class, 'menu'])->name('menu');
});

Route::resource('roles', RoleController::class) ->middleware(['auth', 'verified']);

Route::resource('shops', ShopController::class) ->middleware(['auth', 'verified']);

Route::resource('users', UserController::class) ->middleware(['auth', 'verified']);

Route::resource('company', CompanyController::class) ->middleware(['auth', 'verified']);



// Route::post('/items/{item}', [ItemController::class, 'update'])->name('items.update');

Route::resource('items', ItemController::class) ->middleware(['auth', 'verified']);

Route::resource('cars', CarController::class) ->middleware(['auth', 'verified']);

Route::resource('meetings', MeetingController::class) ->middleware(['auth', 'verified']);


Route::resource('reports', ReportController::class) ->middleware(['auth', 'verified']);

Route::resource('comments', CommentController::class) ->middleware(['auth', 'verified']);



Route::resource('orders', OrderController::class) ->middleware(['auth', 'verified']);



Route::resource('nyukins', NyukinController::class) ->middleware(['auth', 'verified']);






Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/menu', function () {
//     return Inertia::render('Menu');
// })->middleware(['auth', 'verified'])->name('menu');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

require __DIR__.'/auth.php';
