<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SalesController;
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



Route::resource('roles', RoleController::class) ->middleware(['auth', 'verified']);

Route::resource('shops', ShopController::class) ->middleware(['auth', 'verified']);

Route::resource('users', UserController::class) ->middleware(['auth', 'verified']);

Route::resource('company', CompanyController::class) ->middleware(['auth', 'verified']);

Route::resource('work', WorkController::class) ->middleware(['auth', 'verified']);

Route::post('/items/{item}', [ItemController::class, 'update'])->name('items.update');

Route::resource('items', ItemController::class) ->middleware(['auth', 'verified']);

Route::resource('cars', CarController::class) ->middleware(['auth', 'verified']);

Route::resource('meetings', MeetingController::class) ->middleware(['auth', 'verified']);

Route::resource('reports', ReportController::class) ->middleware(['auth', 'verified']);

Route::resource('comments', CommentController::class) ->middleware(['auth', 'verified']);

Route::resource('orders', OrderController::class) ->middleware(['auth', 'verified']);

Route::resource('sales', SalesController::class) ->middleware(['auth', 'verified']);

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
