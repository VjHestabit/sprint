<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

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
    return view('auth.login');
});
Route::get('student/register',[RegisterController::class,'studentView'])->name('studentRegister');
Route::get('teacher/register',[RegisterController::class,'teacherView'])->name('teacherRegister');
Route::post('register/user',[RegisterController::class,'userRegister'])->name('registerUser');
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::middleware(['check_user_role:999,101'])->group(function(){
        Route::resource('students',StudentController::class);
    });
    Route::middleware(['check_user_role:999'])->group(function(){
        Route::resource('teachers',TeacherController::class);
        Route::get('approve/student/{id}/{status}',[StudentController::class,'approveStudent'])->name('approveStudent');
        Route::post('assigned/teacher',[StudentController::class,'assignedTeacher'])->name('assignedTeacher');
    });
});
