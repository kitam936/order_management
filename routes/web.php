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
    Route::post('/items/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::get('/reports/{id}', [ReportController::class, 'index'])->name('reports.index2');
    Route::get('/reports/create/{id}', [ReportController::class, 'create'])->name('reports.create2');
    Route::get('/reports/show/{report}', [ReportController::class, 'show'])->name('reports.show2');
    Route::get('/comments/create/{id}', [CommentController::class, 'comment_create'])->name('comments.create2');
    Route::delete('del_image1', [ReportController::class, 'del_image1'])->name('reports.del_image1');
    Route::delete('del_image2', [ReportController::class, 'del_image2'])->name('reports.del_image2');
    Route::delete('del_image3', [ReportController::class, 'del_image3'])->name('reports.del_image3');
    Route::delete('del_image4', [ReportController::class, 'del_image4'])->name('reports.del_image4');
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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
