<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupController;

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
/* Subject to Change */
Route::get('/test', function () {
    return view('test');
});
Route::get('/assignments', function () {
    return view('/assignments/assignment');
});
Route::post('/assignments', function () {
    return view('/assignments/assignment');
});
Route::post('/detail', function () {
    return view('/assignments/assignment-details');
});
Route::get('/assignment-add', function () {
    return view('/assignments/assignment-add');
});

Route::get('/group-reports', [GroupController::class, 'view_groups']);
Route::get('/edit-grades', [GroupController::class, 'edit_grades']);
Route::post('/update-grades', [GroupController::class, 'update_grades']);