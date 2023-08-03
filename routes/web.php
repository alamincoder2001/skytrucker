<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataEntryController;
use App\Http\Controllers\UserAccessController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\GamingController;


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

// Authentication
Route::group(['middleware' => 'guest'], function() {
    Route::get('/', [AuthenticationController::class, 'login'])->name('login');
    Route::post('/login', [AuthenticationController::class, 'loginCheck'])->name('login.check');
});

Route::group(['middleware' => 'auth'], function() { 
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');

    // Company 
    Route::get('/content', [ContentController::class, 'index'])->name('content.edit');
    Route::get('/get_content', [ContentController::class, 'getContent']);
    Route::post('/update_content', [ContentController::class, 'update']);

    // Area
    Route::get('/area', [AreaController::class, 'index'])->name('area.index');
    Route::get('/get_areas', [AreaController::class, 'getArea']);
    Route::post('/save_area', [AreaController::class, 'store']);
    Route::post('/update_area', [AreaController::class, 'update']);
    Route::post('/delete_area', [AreaController::class, 'destroy']);
    
    // Data Entry
    Route::get('/data/entry', [DataEntryController::class, 'index'])->name('data.index');
    Route::post('/save_data', [DataEntryController::class, 'store']);
    Route::post('/update_data', [DataEntryController::class, 'update']);
    Route::get('/data/list', [DataEntryController::class, 'dataList'])->name('data.list');
    Route::post('/data_list', [DataEntryController::class, 'getDataList']);
    Route::post('/phone_verify_process', [DataEntryController::class, 'phoneVerifyProcess']);
    Route::get('/data/export/{dateFrom}/{dateTo}/{areaId?}/{leaderId?}/{bpId?}', [DataEntryController::class, 'dataExport'])->name('data.export');
    Route::post('/total_data_list', [DataEntryController::class, 'getTotalDataList']);

    // reports
    Route::get('/data/areawise-datalist', [DataEntryController::class, 'areawiseDataList'])->name('data.areawisedataList');
    Route::get('/data/teamleaderwise-datalist', [DataEntryController::class, 'teamleaderwisedataList'])->name('data.teamleaderwisedataList');
    Route::get('/data/bpwise-datalist', [DataEntryController::class, 'bpwisedataList'])->name('data.bpwisedataList');
    
    // Take picture route
    Route::get('/take/picture', [DataEntryController::class, 'takePicture'])->name('take.picture');
    Route::get('/picture/list', [DataEntryController::class, 'pictureList'])->name('picture.list');
    Route::post('/save_picture', [DataEntryController::class, 'savePicture']);
    Route::post('/get_pictures', [DataEntryController::class, 'getPictures']);
    Route::post('/delete_picture', [DataEntryController::class, 'deletePicture']);

    // User Registration
    Route::get('/registration', [AuthenticationController::class, 'registration'])->name('user.registration');
    Route::post('/get_users', [AuthenticationController::class, 'getUser']);
    Route::post('/save_user', [AuthenticationController::class, 'store']);
    Route::post('/update_user', [AuthenticationController::class, 'update']);
    Route::post('/delete_user', [AuthenticationController::class, 'destroy']);
    Route::get('/users', [AuthenticationController::class, 'UserList'])->name('user.list');

    // User Permission
    Route::get('/user/permission/{id}', [UserAccessController::class, 'permission_edit'])->name('user.permission');
    Route::post('/store-permission', [UserAccessController::class, 'store_permission'])->name('store.permission');
    
    // Gaming Route
    Route::get('/gaming', [GamingController::class, 'index'])->name('gaming.index');
    Route::get('/gaming/list', [GamingController::class, 'gamingList'])->name('gaming.list');
    Route::post('/save_gaming', [GamingController::class, 'saveGaming']);
    Route::post('/get_gamings', [GamingController::class, 'getGaming']);
    Route::post('/delete_gaming', [GamingController::class, 'deleteGaming']);

    // Utill
    Route::get('/table', [DashboardController::class, 'table'])->name('table');
    Route::get('/form', [DashboardController::class, 'form'])->name('form');
});