<?php

use App\Http\Controllers\AssessmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\CourseController;

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

 Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/* Subject to Change */
// Route::get('/test', function () {
//     return view('test');
// });
// Route::get('/assignments', function () {
//     return view('/assignments/assignment');
// });
// Route::post('/assignments', function () {
//     return view('/assignments/assignment');
// });
// Route::post('/detail', function () {
//     return view('/assignments/assignment-details');
// });
// Route::get('/assignment-add', function () {
//     return view('/assignments/assignment-add');
// });

// Route::get('/courses', function () {
//     return view('/courses');
// });

// Route::get('/group-reports', [GroupController::class, 'view_groups'])->name('group-reports');
// Route::post('/group-reports', [GroupController::class, 'view_groups'])->name('group-reports');

// Route::middleware('clear.edit.grades')->group(function () {
//     Route::post('/edit-grades', [GroupController::class, 'edit_grades'])->name('edit-grades');
//     Route::get('/edit-grades', [GroupController::class, 'edit_grades'])->name('edit-grades');
// });

// Route::post('/update-grades', [GroupController::class, 'update_grades'])->name('update-grades');
// Route::post('/update-comment', [GroupController::class, 'update_comment'])->name('update-comment');
// Route::post('/delete-group', [GroupController::class, 'delete_group'])->name('delete-group');
// Route::post('/test-something', [GroupController::class, 'test'])->name('test-something');
// Route::view('/file', 'groups/test');

// Route::get('/export', [GroupController::class, 'export'])->name('export');

Route::middleware(['auth'])->group(function () {
    Route::get('/test', function () {
        return view('test');
    });

    Route::get('/assignments',[AssessmentController::class, 'index'])->name('assignments');

    Route::post('/assignments', function () {
        return view('/assignments/assignment');
    });

    Route::post('/detail', [AssessmentController::class, 'viewAssessmentDetail']);

    Route::get('/assignment-add', [AssessmentController::class, 'viewAdd']);
    Route::post('/assignment-add', [AssessmentController::class, 'addAssessment']);
    Route::post('/assignment-section-add', [AssessmentController::class, 'addSection']);
    

    Route::get('/courses', function () {
        return view('/courses');
    });
});


Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);

Route::get('/courses', [CourseController::class, 'get_all_courses'])->name('courses');

Route::get('/group-reports', [GroupController::class, 'view_groups'])->name('group-reports');
Route::post('/group-reports', [GroupController::class, 'view_groups'])->name('group-reports');
Route::post('/delete-group', [GroupController::class, 'delete_group'])->name('delete-group');

Route::middleware('clear.edit.grades')->group(function () {
    Route::post('/edit-grades', [GradeController::class, 'edit_grades'])->name('edit-grades');
    Route::get('/edit-grades', [GradeController::class, 'edit_grades'])->name('edit-grades');
});
Route::post('/update-grades', [GradeController::class, 'update_grades'])->name('update-grades');
Route::post('/update-comment', [GradeController::class, 'update_comment'])->name('update-comment');
Route::post('/import-grades', [GradeController::class, 'import_grades'])->name('import-grades');
Route::view('/file', 'groups/test');

Route::post('/export_grades', [GradeController::class, 'export_grades'])->name('export_grades');
