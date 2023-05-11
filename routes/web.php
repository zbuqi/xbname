<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NamesController;
use App\Http\Controllers\TmpNamesController;
use App\Http\Controllers\UsersController;


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
    return view('welcome');
});


$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function($api){
    $api->post('names/list', [NamesController::class, 'show']);
    $api->post('name/create', [NamesController::class, 'add']);
    $api->post('name/create/excel', [NamesController::class, 'addExcel']);
    $api->get('name/update/beian/tmp_names', [TmpNamesController::class, 'update']);
    $api->post('name/{id}/edit', [NamesController::class, 'edit']);

    $api->post('user/valid/name', [UsersController::class, 'valid']);/*验证*/
    $api->post('user/login', [UsersController::class, 'login']);
    $api->get('user/info', [UsersController::class, 'show']);
});
