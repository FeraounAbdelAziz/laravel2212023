<?php


use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\StatisticsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::resource('patient', PatientController::class); // DONE

Route::post('/device ', [DeviceController::class, 'store']);


Route::post('/register ', [AuthController::class, 'register']);
Route::post('/login ', [AuthController::class, 'login']);
Route::get('/statistics', [StatisticsController::class, 'statistics']);
Route::get('/assignment ', [AssignmentController::class, 'index']);
Route::get('/assignment/{assignment} ', [AssignmentController::class, 'assignment']);
Route::get('/deviceAffect ', [DeviceController::class, 'deviceAffect']);
Route::get('/allDevices ', [DeviceController::class, 'allDevices']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout ', [AuthController::class, 'logout']);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
