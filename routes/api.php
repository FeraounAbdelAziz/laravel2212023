<?php


use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\EnvironmentController;
use App\Http\Controllers\GpsController;
use App\Http\Controllers\HeartBeatController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ShockController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\TemperatureController;
use App\Http\Controllers\UrgentController;
use Illuminate\Support\Facades\Route;




Route::post('/login ', [AuthController::class, 'login']);
Route::post('/register ', [AuthController::class, 'register']);
Route::post('/check-email', [AuthController::class, 'checkEmailVerification']);
Route::post('/resend', [AuthController::class, 'sendEmailVerification']);


Route::group(['middleware' => ['api', 'checkAdminToken:api']], function () {

    //Gps Controller
    Route::post('/getGpsPatient/{idPatient}', [GpsController::class, 'getGpsPatient']);
    Route::post('/lastGpsPatient/{idPatient}', [GpsController::class, 'getLastGpsPatient']);
    Route::post('/getGpsForDateRange/{idPatient}', [GpsController::class, 'getGpsForDateRange']);

    //Temperature Controller
    Route::post('/getTempPatient/{idPatient}', [TemperatureController::class, 'getTempPatient']);
    Route::post('/lastTemperatureRecord/{idPatient}', [TemperatureController::class, 'lastTemperatureRecord']);
    Route::post('/patientTemp/getTemperaturesForDateRange/{idPatient}', [TemperatureController::class, 'getTemperaturesForDateRange']);

    //Environment Controller
    Route::post('/getEnvironmentPatient/{idPatient}', [EnvironmentController::class, 'getEnvironmentPatient']);
    Route::post('/lastEnvironmentRecord/{idPatient}', [EnvironmentController::class, 'lastEnvironmentRecord']);
    Route::post('/getEnvironmentForDateRange/{idPatient}', [EnvironmentController::class, 'getEnvironmentForDateRange']);

    //Shock Controller
    Route::post('/getShockPatient/{idPatient}', [ShockController::class, 'getShockPatient']);
    Route::post('/getShockForDateRange/{idPatient}', [ShockController::class, 'getShockForDateRange']);

    //HeartBeat Controller
    Route::post('/getHBPatient/{idPatient}', [HeartBeatController::class, 'getHBPatient']);
    Route::post('/lastHBRecord/{idPatient}', [HeartBeatController::class, 'lastHBRecord']);
    Route::post('/getHeartBeatForDateRange/{idPatient}', [HeartBeatController::class, 'getHeartBeatForDateRange']);


    //User Controller
    Route::get('/displayAccounts', [DoctorController::class, 'displayAccounts']);
    Route::delete('/deleteUser/{id} ', [DoctorController::class, 'deleteUser']);
    Route::post('/createAdmin ', [DoctorController::class, 'createAdmin']);
    Route::put('/updateUser/{id}', [DoctorController::class, 'updateUser']);

    //Assignment Controller
    Route::get('/assignment/{id}', [AssignmentController::class, 'assignment']);
    Route::post('/addAssignment', [AssignmentController::class, 'addAssignment']);
    Route::get('/getIdDevPat', [AssignmentController::class, 'getIdDevPat']);
    Route::delete('/deleteAssignment/{id}', [AssignmentController::class, 'deleteAssignment']);
    Route::get('/assignment ', [AssignmentController::class, 'index']);
    Route::get('/assignment/{assignment} ', [AssignmentController::class, 'assignment']);

    //Patient Controller
    Route::resource('patient', PatientController::class); // DONE
    Route::get('/statistics', [StatisticsController::class, 'statistics']);
    Route::post('/logout ', [AuthController::class, 'logout']);

    //Device Controller
    Route::delete('/device/{id} ', [DeviceController::class, 'destroy']);
    Route::post('/device ', [DeviceController::class, 'store']);
    Route::post('/device/{id} ', [DeviceController::class, 'search']);
    Route::get('/deviceAssignment ', [DeviceController::class, 'deviceAssignment']);

    // Urgent Controller
    Route::get('/urgentCase', [UrgentController::class, 'urgentCase']);
});
