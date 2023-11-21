<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



//onlylog
Route::middleware('auth')->group(function () {
	//route ajax
	Route::post('infoloc', 'App\Http\Controllers\AjaxController@infoloc');
	///end route ajax


	Route::get('/', [ 'as' => 'documenti_utili', 'uses' => 'App\Http\Controllers\MainController@documenti_utili']);


	Route::get('documenti_utili', [ 'as' => 'documenti_utili', 'uses' => 'App\Http\Controllers\MainController@documenti_utili']);

	Route::post('documenti_utili', [ 'as' => 'documenti_utili', 'uses' => 'App\Http\Controllers\MainController@documenti_utili']);
	
	
	
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
