<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DomainController;

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

Route::resource('domains', DomainController::class)->only(['index', 'store', 'show']);;
Route::get('/', [DomainController::class, 'create'])->name('domains.create');
Route::post('/domains/{id}/check', [DomainController::class, 'check'])->name('domains.check');