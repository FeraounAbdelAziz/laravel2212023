<?php


use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\GpsController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\TemperatureController;
use Illuminate\Support\Facades\Route;




Route::post('/login ', [AuthController::class, 'login']);
Route::post('/register ', [AuthController::class, 'register']);
Route::post('/check-email', [AuthController::class, 'checkEmailVerification']);
Route::post('/resend', [AuthController::class, 'sendEmailVerification']);


Route::group(['middleware' => ['api', 'checkAdminToken:api']], function () {



    //Gps Controller
    Route::post('/getGpsPatient/{idPatient}', [GpsController::class, 'getGpsPatient']);
    Route::post('/getLastGpsPatient/{idPatient}', [GpsController::class, 'getLastGpsPatient']);
    Route::post('/getGpsForDateRange/{idPatient}', [GpsController::class, 'getGpsForDateRange']);

    //Temperature Controller
    Route::post('/getTempPatient/{idPatient}', [TemperatureController::class, 'getTempPatient']);
    Route::post('/lastTemperatureRecord/{idPatient}', [TemperatureController::class, 'lastTemperatureRecord']);
    Route::post('/patientTemp/getTemperaturesForDateRange/{idPatient}', [TemperatureController::class, 'getTemperaturesForDateRange']);


    Route::resource('patient', PatientController::class); // DONE
    Route::get('/statistics', [StatisticsController::class, 'statistics']);
    Route::post('/logout ', [AuthController::class, 'logout']);

    Route::delete('/device/{id} ', [DeviceController::class, 'destroy']);
    Route::post('/device ', [DeviceController::class, 'store']);
    Route::post('/device/{id} ', [DeviceController::class, 'search']);
    Route::get('/deviceAssignment ', [DeviceController::class, 'deviceAssignment']);


    Route::get('/assignment ', [AssignmentController::class, 'index']);
    Route::get('/assignment/{assignment} ', [AssignmentController::class, 'assignment']);
});
