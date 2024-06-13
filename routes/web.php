<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\PolylineController;
use App\Http\Controllers\PolygonController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',[MapController::class, 'index'])->name('index');
Route::get('/table',[MapController::class, 'table'])->name('table');

//Create Point
Route::post('/store-point',[PointController::class, 'store'])->name('store-point');
//Delete Point
Route::delete('delete-point/{id}', [PointController::class, 'destroy'])->name('delete-point');
//Edit Point
Route::get('/edit-point/{id}', [PointController::class, 'edit'])->name('edit-point');
//Update Polygon
Route::patch('/update-point/{id}',[PointController::class, 'update'])->name('update-point');

//Create Polyline
Route::post('/store-polyline',[PolylineController::class, 'store'])->name('store-polyline');
//Delete Polyline
Route::delete('delete-polyline/{id}', [PolylineController::class, 'destroy'])->name('delete-polyline');
//Edit Polyline
Route::get('/edit-polyline/{id}', [PolylineController::class, 'edit'])->name('edit-polyline');
//Update Polyline
Route::patch('/update-polyline/{id}',[PolylineController::class, 'update'])->name('update-polyline');

//Create Polygon
Route::post('/store-polygon',[PolygonController::class, 'store'])->name('store-polygon');
//Delete Polygon
Route::delete('delete-polygon/{id}', [PolygonController::class, 'destroy'])->name('delete-polygon');
//Edit Polygon
Route::get('/edit-polygon/{id}', [PolygonController::class, 'edit'])->name('edit-polygon');
//Update Polygon
Route::patch('/update-polygon/{id}',[PolygonController::class, 'update'])->name('update-polygon');


Route::get('/about', function () {
    return view('about');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//table, mengatur agar ketika pengguna mengakses URL /table-point, aplikasi akan memanggil metode table pada PointController.
Route::get('/table-point', [PointController::class, 'table'])->name('table-point');

//table
Route::get('/table-polyline', [PolylineController::class, 'table'])->name('table-polyline');

//table
Route::get('/table-polygon', [PolygonController::class, 'table'])->name('table-polygon');

require __DIR__.'/auth.php';