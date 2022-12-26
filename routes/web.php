<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PolicyController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LikeController;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


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

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login_proc', [UserController::class, 'login'])->name('login_proc');
Route::middleware('auth:sanctum')->get('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/', function () {
    $list = UserController::get_list();
    //dd($list->data[0]->data);
    return view('user_list', ['list' => $list]);
});

Route::get('/list', function () {
    $list = QuestionController::get_list();
    return view('main', ['list' => $list]);
})->name('list');

Route::get('/search/{tag}', function (Request $request) {
    $list = QuestionController::get_list_search($request);
    return view('main', ['list' => $list]);
})->name('search');

Route::middleware('auth:sanctum')->post('/regist_que', [QuestionController::class, 'regist'])->name('regist_que');
Route::get('/view_que/{question_id}', [QuestionController::class, 'view'])->name('view_que');
Route::middleware('auth:sanctum')->post('/regist_answer', [AnswerController::class, 'regist'])->name('regist_answer');
Route::get('/page/{user_id}', [UserController::class, 'page'])->name('page');
Route::middleware('auth:sanctum')->get('/myque', [QuestionController::class, 'myque'])->name('myque');
Route::middleware('auth:sanctum')->get('/mypage', [UserController::class, 'mypage'])->name('mypage');
Route::get('/user/regist', [UserController::class, 'regist'])->name('user_resegist');
Route::post('/user/regist_proc', [UserController::class, 'regist_proc'])->name('user_regist_proc');
Route::middleware('auth:sanctum')->post('/like', [LikeController::class, 'toggle'])->name('like');
Route::middleware('auth:sanctum')->get('/delete/que/{id}', [QuestionController::class, 'delete'])->name('delete_que');
