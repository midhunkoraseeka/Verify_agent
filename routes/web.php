<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminControllers\AuthController;
use App\Http\Controllers\AdminControllers\AgentController;
use App\Http\Controllers\AdminControllers\UserController;
use App\Http\Controllers\AdminControllers\PropertyController;
use App\Http\Controllers\AdminControllers\AdController;
use App\Http\Controllers\AdminControllers\LoanController;
use App\Http\Controllers\AdminControllers\SurveyorController;
use App\Http\Controllers\AdminControllers\VasthuController;
use App\Http\Controllers\AdminControllers\AdvocateController;
use App\Http\Controllers\AdminControllers\ArchitectureController;
use App\Http\Controllers\AdminControllers\FacingController;
use App\Http\Controllers\AdminControllers\BhkTypeController;
use App\Http\Controllers\AdminControllers\FloorController;
use App\Http\Controllers\AdminControllers\ParkingController;
use App\Http\Controllers\AdminControllers\RoadSizeController;
use App\Http\Controllers\AdminControllers\ApprovalController;
use App\Http\Controllers\AdminControllers\LandTypeController;
use App\Http\Controllers\AdminControllers\AdTypeController;
use App\Http\Controllers\AdminControllers\LegalServiceController;
use App\Http\Controllers\AdminControllers\ProjectTypeController;
use App\Http\Controllers\AdminControllers\VasthuServiceController;
use App\Http\Controllers\AdminControllers\LoanTypeController;
use App\Http\Controllers\AdminControllers\SurveyServiceController;
use App\Http\Controllers\AdminControllers\StateController;
use App\Http\Controllers\AdminControllers\DistrictController;
use App\Http\Controllers\AdminControllers\ConstituencyController;


Route::get('admin/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('admin/login', [AuthController::class, 'login'])->name('admin.login');
Route::post('admin/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [AgentController::class, 'index'])->name('dashboard');

    Route::get('/manageAgent', [AgentController::class, 'manage'])->name('manageAgent');
    Route::get('/createAgent', [AgentController::class, 'create'])->name('createAgent');
    Route::post('/storeAgent', [AgentController::class, 'store'])->name('storeAgent');
    Route::get('/editAgent/{id}', [AgentController::class, 'edit'])->name('editAgent');
    Route::post('/updateAgent/{id}', [AgentController::class, 'update'])->name('updateAgent');
    Route::get('/deleteAgent/{id}', [AgentController::class, 'delete'])->name('deleteAgent');
    Route::get('/restoreAgent/{id}', [AgentController::class, 'restore'])->name('restoreAgent');
    Route::get('/exportAgents', [AgentController::class, 'exportAgents'])->name('exportAgents');


    Route::get('/manageUser', [UserController::class, 'manageUser'])->name('manageUser');
    Route::get('/createUser', [UserController::class, 'createUser'])->name('createUser');
    Route::post('/storeUser', [UserController::class, 'storeUser'])->name('storeUser');
    Route::get('/editUser/{id}', [UserController::class, 'editUser'])->name('editUser');
    Route::match(['post', 'put'], '/updateUser/{id}', [UserController::class, 'updateUser'])->name('updateUser');
    Route::get('/deleteUser/{id}', [UserController::class, 'deleteUser'])->name('deleteUser');
    Route::get('/users/exportUsers', [UserController::class, 'exportUsers'])->name('exportUsers');

    Route::get('/manageProperty', [PropertyController::class, 'manageProperty'])->name('manageProperty');
    Route::get('/property/createProperty', [PropertyController::class, 'createProperty'])->name('createProperty');
    Route::post('/property/storeProperty', [PropertyController::class, 'storeProperty'])->name('storeProperty');
    Route::get('/property/deleteProperty/{id}', [PropertyController::class, 'deleteProperty'])->name('deleteProperty');
    Route::get('/exportProperties', [PropertyController::class, 'exportProperties'])->name('exportProperties');
    Route::get('/editProperty/{id}', [PropertyController::class, 'editProperty'])->name('editProperty');
    Route::post('/updateProperty/{id}', [PropertyController::class, 'updateProperty'])->name('updateProperty');


    Route::get('/manageAds', [AdController::class, 'manage'])->name('manageAds');
    Route::get('/createAd', [AdController::class, 'create'])->name('createAd');
    Route::post('/storeAd', [AdController::class, 'store'])->name('storeAd');
    Route::get('/editAd/{id}', [AdController::class, 'edit'])->name('editAd');
    Route::post('/updateAd/{id}', [AdController::class, 'update'])->name('updateAd');
    Route::get('/deleteAd/{id}', [AdController::class, 'delete'])->name('deleteAd');
    Route::get('/restoreAd/{id}', [AdController::class, 'restore'])->name('restoreAd');
    Route::get('/exportAds', [AdController::class, 'exportAds'])->name('exportAds');

    Route::get('/manageLoan', [LoanController::class, 'manage'])->name('manageLoan');
    Route::get('/createLoan', [LoanController::class, 'create'])->name('createLoan');
    Route::post('/storeLoan', [LoanController::class, 'store'])->name('storeLoan');
    Route::get('/editLoan/{id}', [LoanController::class, 'edit'])->name('editLoan');
    Route::post('/updateLoan/{id}', [LoanController::class, 'update'])->name('updateLoan');
    Route::post('/deleteLoan/{id}', [LoanController::class, 'delete'])->name('deleteLoan');
    Route::get('/restoreLoan/{id}', [LoanController::class, 'restore'])->name('restoreLoan');
    Route::get('/exportLoans', [LoanController::class, 'exportLoans'])->name('exportLoans');

    Route::get('/mmanageSurveyor', [SurveyorController::class, 'manage'])->name('manageSurveyor');
    Route::get('/createSurveyor', [SurveyorController::class, 'create'])->name('createSurveyor');
    Route::post('/storeSurveyor', [SurveyorController::class, 'store'])->name('storeSurveyor');
    Route::get('/edit-surveyor/{id}', [SurveyorController::class, 'edit'])->name('editSurveyor');
    Route::post('/update-surveyor/{id}', [SurveyorController::class, 'update'])->name('updateSurveyor');
    Route::post('/deleteSurveyor/{id}', [SurveyorController::class, 'delete'])->name('deleteSurveyor');
    Route::get('/restoreSurveyor/{id}', [SurveyorController::class, 'restore'])->name('restoreSurveyor');
    Route::get('/exportSurveyors', [SurveyorController::class, 'exportSurveyors'])->name('exportSurveyors');


    Route::get('/manageVasthu', [VasthuController::class, 'manage'])->name('manageVasthu');
    Route::get('/createVasthu', [VasthuController::class, 'create'])->name('createVasthu');
    Route::post('/storeVasthu', [VasthuController::class, 'store'])->name('storeVasthu');
    Route::get('/editVasthu/{id}', [VasthuController::class, 'edit'])->name('editVasthu');
    Route::post('/updateVasthu/{id}', [VasthuController::class, 'update'])->name('updateVasthu');
    Route::post('/deleteVasthu/{id}', [VasthuController::class, 'delete'])->name('deleteVasthu');
    Route::get('/restoreVasthu/{id}', [VasthuController::class, 'restore'])->name('restoreVasthu');
    Route::get('/exportVasthu', [VasthuController::class, 'exportVasthu'])->name('exportVasthu');

    Route::get('/manageAdvocate', [AdvocateController::class, 'manage'])->name('manageAdvocate');
    Route::get('/createAdvocate', [AdvocateController::class, 'create'])->name('createAdvocate');
    Route::post('/storeAdvocate', [AdvocateController::class, 'store'])->name('storeAdvocate'); // Handles the POST
    Route::get('/editAdvocate/{id}', [AdvocateController::class, 'edit'])->name('editAdvocate');
    Route::post('/updateAdvocate/{id}', [AdvocateController::class, 'update'])->name('updateAdvocate');
    Route::post('/deleteAdvocate/{id}', [AdvocateController::class, 'delete'])->name('deleteAdvocate');
    Route::get('/exportAdvocates', [AdvocateController::class, 'exportAdvocates'])->name('exportAdvocates');

    Route::get('/manageArchitecture', [ArchitectureController::class, 'manage'])->name('manageArchitecture');
    Route::get('/createArchitecture', [ArchitectureController::class, 'create'])->name('createArchitecture');
    Route::post('/storeArchitecture', [ArchitectureController::class, 'store'])->name('storeArchitecture');
    Route::get('/editArchitecture/{id}', [ArchitectureController::class, 'edit'])->name('editArchitecture');
    Route::match(['POST', 'PUT'], '/updateArchitecture/{id}', [ArchitectureController::class, 'update'])->name('updateArchitecture');
    Route::post('/deleteArchitecture/{id}', [ArchitectureController::class, 'delete'])->name('deleteArchitecture');
    Route::get('/exportArchitectures', [ArchitectureController::class, 'exportArchitectures'])->name('exportArchitectures');

    Route::get('/manageFacing', [FacingController::class, 'manageFacing'])->name('manageFacing');
    Route::get('/createFacing', [FacingController::class, 'createFacing'])->name('createFacing');
    Route::post('/storeFacing', [FacingController::class, 'storeFacing'])->name('storeFacing');
    Route::get('/editFacing/{id}', [FacingController::class, 'editFacing'])->name('editFacing');
    Route::post('/updateFacing/{id}', [FacingController::class, 'updateFacing'])->name('updateFacing');
    Route::get('/deleteFacing/{id}', [FacingController::class, 'deleteFacing'])->name('deleteFacing');

    Route::get('/manageBhkType', [BhkTypeController::class, 'manageBhkType'])->name('manageBhkType');
    Route::post('/storeBhkType', [BhkTypeController::class, 'storeBhkType'])->name('storeBhkType');
    Route::get('/deleteBhkType/{id}', [BhkTypeController::class, 'deleteBhkType'])->name('deleteBhkType');
    Route::post('/updateBhkType/{id}', [BhkTypeController::class, 'updateBhkType'])->name('updateBhkType');

    Route::get('/manageFloor', [FloorController::class, 'manageFloor'])->name('manageFloor');
    Route::post('/storeFloor', [FloorController::class, 'storeFloor'])->name('storeFloor');
    Route::post('/updateFloor/{id}', [FloorController::class, 'updateFloor'])->name('updateFloor');
    Route::get('/deleteFloor/{id}', [FloorController::class, 'deleteFloor'])->name('deleteFloor');

    Route::get('/manageParking', [ParkingController::class, 'manageParking'])->name('manageParking');
    Route::post('/storeParking', [ParkingController::class, 'storeParking'])->name('storeParking');
    Route::post('/updateParking/{id}', [ParkingController::class, 'updateParking'])->name('updateParking');
    Route::get('/deleteParking/{id}', [ParkingController::class, 'deleteParking'])->name('deleteParking');

    Route::get('/manageRoadSize', [RoadSizeController::class, 'manageRoadSize'])->name('manageRoadSize');
    Route::post('/storeRoadSize', [RoadSizeController::class, 'storeRoadSize'])->name('storeRoadSize');
    Route::post('/updateRoadSize/{id}', [RoadSizeController::class, 'updateRoadSize'])->name('updateRoadSize');
    Route::get('/deleteRoadSize/{id}', [RoadSizeController::class, 'deleteRoadSize'])->name('deleteRoadSize');

    Route::get('/manageApproval', [ApprovalController::class, 'manageApproval'])->name('manageApproval');
    Route::post('/storeApproval', [ApprovalController::class, 'storeApproval'])->name('storeApproval');
    Route::post('/updateApproval/{id}', [ApprovalController::class, 'updateApproval'])->name('updateApproval');
    Route::get('/deleteApproval/{id}', [ApprovalController::class, 'deleteApproval'])->name('deleteApproval');

    Route::get('/manageLandType', [LandTypeController::class, 'manageLandType'])->name('manageLandType');
    Route::post('/storeLandType', [LandTypeController::class, 'storeLandType'])->name('storeLandType');
    Route::post('/updateLandType/{id}', [LandTypeController::class, 'updateLandType'])->name('updateLandType');
    Route::get('/deleteLandType/{id}', [LandTypeController::class, 'deleteLandType'])->name('deleteLandType');

    Route::get('/manageAdType', [AdTypeController::class, 'manageAdType'])->name('manageAdType');
    Route::post('/storeAdType', [AdTypeController::class, 'storeAdType'])->name('storeAdType');
    Route::post('/updateAdType/{id}', [AdTypeController::class, 'updateAdType'])->name('updateAdType');
    Route::get('/deleteAdType/{id}', [AdTypeController::class, 'deleteAdType'])->name('deleteAdType');

    Route::get('/manageLegalService', [LegalServiceController::class, 'manageLegalService'])->name('manageLegalService');
    Route::post('/storeLegalService', [LegalServiceController::class, 'storeLegalService'])->name('storeLegalService');
    Route::post('/updateLegalService/{id}', [LegalServiceController::class, 'updateLegalService'])->name('updateLegalService');
    Route::get('/deleteLegalService/{id}', [LegalServiceController::class, 'deleteLegalService'])->name('deleteLegalService');

    // Project Type Master Routes
    Route::get('/manageProjectType', [ProjectTypeController::class, 'manageProjectType'])->name('manageProjectType');
    Route::post('/storeProjectType', [ProjectTypeController::class, 'storeProjectType'])->name('storeProjectType');
    Route::post('/updateProjectType/{id}', [ProjectTypeController::class, 'updateProjectType'])->name('updateProjectType');
    Route::get('/deleteProjectType/{id}', [ProjectTypeController::class, 'deleteProjectType'])->name('deleteProjectType');

    Route::get('/manageVasthuService', [VasthuServiceController::class, 'manageVasthuService'])->name('manageVasthuService');
    Route::post('/storeVasthuService', [VasthuServiceController::class, 'storeVasthuService'])->name('storeVasthuService');
    Route::post('/updateVasthuService/{id}', [VasthuServiceController::class, 'updateVasthuService'])->name('updateVasthuService');
    Route::get('/deleteVasthuService/{id}', [VasthuServiceController::class, 'deleteVasthuService'])->name('deleteVasthuService');


    Route::get('/manageLoanType', [LoanTypeController::class, 'manageLoanType'])->name('manageLoanType');
    Route::post('/storeLoanType', [LoanTypeController::class, 'storeLoanType'])->name('storeLoanType');
    Route::post('/updateLoanType/{id}', [LoanTypeController::class, 'updateLoanType'])->name('updateLoanType');
    Route::get('/deleteLoanType/{id}', [LoanTypeController::class, 'deleteLoanType'])->name('deleteLoanType');


    Route::get('/manageSurveyService', [SurveyServiceController::class, 'manageSurveyService'])->name('manageSurveyService');
    Route::post('/storeSurveyService', [SurveyServiceController::class, 'storeSurveyService'])->name('storeSurveyService');
    Route::post('/updateSurveyService/{id}', [SurveyServiceController::class, 'updateSurveyService'])->name('updateSurveyService');
    Route::get('/deleteSurveyService/{id}', [SurveyServiceController::class, 'deleteSurveyService'])->name('deleteSurveyService');

    Route::get('/manageState', [StateController::class, 'manageState'])->name('manageState');
    Route::post('/storeState', [StateController::class, 'storeState'])->name('storeState');
    Route::post('/updateState/{id}', [StateController::class, 'updateState'])->name('updateState');
    Route::get('/deleteState/{id}', [StateController::class, 'deleteState'])->name('deleteState');

    Route::get('/manageDistrict', [DistrictController::class, 'manageDistrict'])->name('manageDistrict');
    Route::post('/storeDistrict', [DistrictController::class, 'storeDistrict'])->name('storeDistrict');
    Route::post('/updateDistrict/{id}', [DistrictController::class, 'updateDistrict'])->name('updateDistrict');
    Route::get('/deleteDistrict/{id}', [DistrictController::class, 'deleteDistrict'])->name('deleteDistrict');
    Route::get('/get-districts/{state_id}', [DistrictController::class, 'getDistricts']);   

    Route::get('/manageConstituency', [ConstituencyController::class, 'manage'])->name('manageConstituency');
Route::post('/storeConstituency', [ConstituencyController::class, 'store'])->name('storeConstituency');
Route::post('/updateConstituency/{id}', [ConstituencyController::class, 'update'])->name('updateConstituency');
Route::get('/deleteConstituency/{id}', [ConstituencyController::class, 'delete'])->name('deleteConstituency');
});



Route::get('/', function () {
    return redirect()->route('login');
});