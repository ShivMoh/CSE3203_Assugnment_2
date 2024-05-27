<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\AuthController;



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

Route::get('/', function () {
    return redirect('login');
 });

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

Route::get('/group-reports', [GroupController::class, 'view_groups'])->name('group-reports');
Route::post('/group-reports', [GroupController::class, 'view_groups'])->name('group-reports');

Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);


Route::middleware('clear.edit.grades')->group(function () {
    Route::post('/edit-grades', [GroupController::class, 'edit_grades'])->name('edit-grades');
    Route::get('/edit-grades', [GroupController::class, 'edit_grades'])->name('edit-grades');
});
Route::post('/update-grades', [GroupController::class, 'update_grades'])->name('update-grades');
Route::post('/update-comment', [GroupController::class, 'update_comment'])->name('update-comment');
Route::post('/delete-group', [GroupController::class, 'delete_group'])->name('delete-group');
