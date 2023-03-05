<?php


use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\StatisticsController;
use Illuminate\Support\Facades\Route;




Route::post('/login ', [AuthController::class, 'login']);
Route::post('/register ', [AuthController::class, 'register']);
Route::post('/check-email', [AuthController::class, 'checkEmailVerification']);
Route::post('/resend', [AuthController::class, 'sendEmailVerification']);

Route::group(['middleware' => ['api', 'checkAdminToken:api']], function () {

    Route::get('/statistics', [StatisticsController::class, 'statistics']); 
    Route::resource('patient', PatientController::class); // DONE
    Route::post('/logout ', [AuthController::class, 'logout']);
    Route::post('/device ', [DeviceController::class, 'store']);
    Route::delete('/device/{id} ', [DeviceController::class, 'destroy']);
    Route::get('/device/{id} ', [DeviceController::class, 'search']);
    Route::get('/deviceAffect ', [DeviceController::class, 'deviceAffect']);
    Route::get('/allDevices ', [DeviceController::class, 'allDevices']);
    Route::get('/assignment ', [AssignmentController::class, 'index']);
    Route::get('/assignment/{assignment} ', [AssignmentController::class, 'assignment']);
});
