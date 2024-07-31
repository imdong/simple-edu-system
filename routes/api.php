<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// 登陆接口
Route::post('/auth/login', [App\Http\Controllers\AuthController::class, 'login']);

// 教师相关路由
Route::group([
    'middleware' => 'auth:teacher',
    'prefix'     => 'teacher',
], function () {
    // 查看登陆用户身份(调试)
    Route::get('/user', [App\Http\Controllers\AuthController::class, 'user']);

    // 课程管理
    Route::apiResource('courses', App\Http\Controllers\CourseController::class);

    // 账单管理
    Route::apiResource('invoices', App\Http\Controllers\InvoiceController::class);
});

// 学生相关路由
Route::group([
    'middleware' => 'auth:student',
    'prefix'     => 'student',
], function () {
    // 查看登陆用户身份(调试)
    Route::get('/user', [App\Http\Controllers\AuthController::class, 'user']);

    // 课程管理
    Route::apiResource('courses', App\Http\Controllers\CourseController::class);
});
