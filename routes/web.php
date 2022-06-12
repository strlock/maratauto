<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarsController;
use App\Http\Controllers\CitiesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return view('welcome');
});

Auth::routes();

Route::get('/home', [CarsController::class, 'index'])->name('home');
Route::post('/new', [CarsController::class, 'save'])->name('save');
Route::get('/cars', [CarsController::class, 'indexAjax'])->name('cars');
Route::get('/photo/{id}/{width?}', [CarsController::class, 'photo'])->name('storageFile');
Route::get('/cities/{state_id}', [CitiesController::class, 'index'])->name('cities');
