<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PolicyController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\GoodsController;

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

Route::get('/', function () {
    $list = QuestionController::get_list();
    return view('main', ['list' => $list]);
})->name('main');

Route::get('/list', function () {
    $list = QuestionController::get_list();
    return view('main', ['list' => $list]);
})->name('list');

Route::middleware('auth:sanctum')->post('/regist_question', [QuestionController::class, 'regist'])->name('regist_question');
Route::get('/view_question', [QuestionController::class, 'view'])->name('view_question');
Route::middleware('auth:sanctum')->post('/regist_answer', [AnswerController::class, 'regist'])->name('regist_answer');
Route::middleware('auth:sanctum')->get('/page/{user_id}', [UserController::class, 'page'])->name('page');
Route::get('/user/regist', [UserController::class, 'regist'])->name('user_resegist');
Route::post('/user/regist_proc', [UserController::class, 'regist_proc'])->name('user_regist_proc');

