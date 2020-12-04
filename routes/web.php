<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\DomainCheckController;

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
    /** @return Illuminate\View\View*/
    return  view('index');
})->name('home');

Route::resource('domains', DomainController::class)->only(['index', 'store', 'show']);
Route::resource('domains.checks', DomainCheckController::class)->only(['store']);
