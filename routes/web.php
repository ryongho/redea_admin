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
Route::middleware('auth:sanctum')->get('/accept', [UserController::class, 'accept'])->name('accept');
Route::middleware('auth:sanctum')->get('/un_accept', [UserController::class, 'un_accept'])->name('un_accept');

Route::middleware('auth:sanctum')->get('/', function () {
    return redirect('/login'); 
});

Route::middleware('auth:sanctum')->get('/logout', [UserController::class, 'logout'])->name('logout');

Route::middleware('auth:sanctum')->get('/user_list', function (Request $request) {
    $list = UserController::get_list($request);
    return view('user_list', ['list' => $list]);
})->name('user_list');

Route::middleware('auth:sanctum')->get('/wait_list', function (Request $request) {
    $list = UserController::get_wait_list($request);
    return view('wait_list', ['list' => $list]);
})->name('wait_list');

Route::middleware('auth:sanctum')->get('/table_list', function (Request $request) {
    $list = UserController::get_table_list($request);
    return view('table_list', ['list' => $list]);
})->name('table_list');

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
