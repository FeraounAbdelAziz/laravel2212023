<?php


use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\StatisticsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



/*START Route For StatisticsController*/
Route::get('/Statistics', [StatisticsController::class, 'Statistics']);
/*END Route For StatisticsController*/

Route::post('/register ', [AuthController::class, 'register']);

Route::get('/assignment ', [AssignmentController::class, 'index']);
Route::get('/assignment/{assignment} ', [AssignmentController::class, 'assignment']);
Route::get('/deviceAffect ', [DeviceController::class, 'deviceAffect']);
Route::get('/allDevices ', [DeviceController::class, 'allDevices']);
Route::resource('patient',PatientController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
