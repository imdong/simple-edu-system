<?php

use App\Http\Controllers\AuthController;
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
Route::post('/auth/login', [AuthController::class, 'login']);

// 教师相关路由
Route::group([
    'middleware' => 'auth:teacher',
], function () {
    // 查看登陆用户身份(调试)
    Route::get('/auth/teacher', [AuthController::class, 'user']);
});

// 学生相关路由
Route::group([
    'middleware' => 'auth:student',
], function () {
    // 查看登陆用户身份(调试)
    Route::get('/auth/student', [AuthController::class, 'user']);
});
