<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/login', [ApiController::class, 'authenticate']);
Route::post('/register', [ApiController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('/logout', [ApiController::class, 'logout']);
    Route::get('/get_user', [ApiController::class, 'get_user']);
    
    Route::get('/get_area', [ApiController::class, 'getAreas']);
    Route::post('/save_data', [ApiController::class, 'dataStore']);
    Route::post('/get_data', [ApiController::class, 'getData']);
    Route::post('/phone_verify', [ApiController::class, 'phoneVerifyProcess']);
    Route::post('/type_wise_user', [ApiController::class, 'getTypeWiseUser']);
    
    // Picture api
    Route::post('/save_picture', [ApiController::class, 'savePicture']);
    Route::post('/get_picture', [ApiController::class, 'getPicture']);
    
    // Gaming api
    Route::post('/save_gaming', [ApiController::class, 'saveGaming']);
    Route::post('/get_gaming', [ApiController::class, 'getGaming']);
    
});

