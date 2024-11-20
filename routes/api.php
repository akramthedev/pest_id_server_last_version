<?php



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\SerreController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ForgotPass;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\BroadcastController;
use App\Http\Controllers\PlaqueController;
use App\Http\Controllers\BookdemoController;



Route::middleware(['throttle:api'])->group(function () {
    
    Route::post('register', [UserController::class, 'register']);     
    Route::post('register2', [UserController::class, 'register2']);                           
    Route::post('login', [UserController::class, 'login']);  

    Route::middleware(['auth:sanctum'])->group(function () {

            Route::get('user_is_welcomed_done/{id}', [UserController::class, 'UserIsWelcomedDone']); 
            Route::get('notice1/{id}', [UserController::class, 'notice1']); 
            Route::get('notice2/{id}', [UserController::class, 'notice2']); 
            Route::get('notice3/{id}', [UserController::class, 'notice3']); 
            Route::get('notice4/{id}', [UserController::class, 'notice4']); 
            Route::get('notice5/{id}', [UserController::class, 'notice5']); 
            Route::get('notice6/{id}', [UserController::class, 'notice6']); 
            Route::get('notice7/{id}', [UserController::class, 'notice7']); 
            Route::get('notice8/{id}', [UserController::class, 'notice8']); 
            Route::get('notice9/{id}', [UserController::class, 'notice9']);
            Route::get('activate-np/{id}', [UserController::class, 'activateNP']); 
            Route::get('activate-an/{id}', [UserController::class, 'activateAN']); 
            Route::get('activate-maj/{id}', [UserController::class, 'activateMAJ']); 
            Route::get('activate-ja/{id}', [UserController::class, 'activateJA']); 
            Route::get('desactivate-np/{id}', [UserController::class, 'desactivateNP']); 
            Route::get('desactivate-an/{id}', [UserController::class, 'desactivateAN']); 
            Route::get('desactivate-maj/{id}', [UserController::class, 'desactivateMAJ']); 
            Route::get('desactivate-ja/{id}', [UserController::class, 'desactivateJA']); 
            Route::get('refreshAllParams/{id}', [UserController::class, 'refreshAllParams']); 
            Route::delete('deleteAllPredictionPerUser/{id}', [PredictionController::class, 'deleteAllPredictionPerUser']);
            Route::get('getAllActivitiesByUser/{id}', [ActivityController::class, 'getAllActivitiesByUser']);
            Route::delete('deleteAllActivityByUser/{id}', [ActivityController::class, 'deleteAllActivityByUser']);
            Route::post('broadcast', [BroadcastController::class, 'createBroadcast']); 
            Route::delete('broadcast', [BroadcastController::class, 'deleteBroadcast']); 
            Route::patch('broadcast', [BroadcastController::class, 'updateBroadcast']); 
            Route::get('broadcast', [BroadcastController::class, 'getBroadcast']);
            Route::post('updatePasswordByAdmin/{id}', [UserController::class, 'updatePasswordByAdmin']);
            Route::post('createActivity/{id}', [ActivityController::class, 'createActivity']);
            Route::post('createActivityByEmail', [ActivityController::class, 'createActivityByEmail']);
            Route::post('createActivityByEmail2', [ActivityController::class, 'createActivityByEmail2']);
            Route::get('farmANDserre/{idFarm}/{idSerre}/{idUser}', [FarmController::class, 'farmANDserre']);  
            Route::get('getAllFarmsWithTheirSerres/{idUser}', [FarmController::class, 'getAllFarmsWithTheirSerres']);  
            Route::get('farmANDserre2/{idFarm}/{idSerre}/{idUser}/{role}', [FarmController::class, 'farmANDserreModified']);
            Route::get('users', [UserController::class, 'getAllUsers']); 
            Route::get('usersNonAccepted', [UserController::class, 'getAllUsersNonAccepted']); 
            Route::get('user', [UserController::class, 'getUser']); 
            Route::get('user-other-data/{id}', [UserController::class, 'getOtherDataOfUserInDashboard']); 
            Route::get('getAdminIdFromUserId/{idUser}', [UserController::class, 'getAdminIdFromUserId']); 
            Route::get('user/{id}', [UserController::class, 'getUserById']); 
            Route::get('getUserByIdAndHisStaffData/{id}', [UserController::class, 'getUserByIdAndHisStaffData']); 
            Route::post('updateUserInfos/{idUser}', [UserController::class, 'updateUser']); 
            Route::patch('user-type', [UserController::class, 'updateUserType']); 
            Route::delete('deleteUserStaffNotAdmin/{id}', [UserController::class, 'deleteUserStaffNotAdmin']); 
            Route::delete('deleteUserWhoIsAdmin/{id}', [UserController::class, 'deleteUserWhoIsAdmin']); 
            Route::get('updateUserRestriction/{id}/{access}', [UserController::class, 'updateUserRestriction']); 
            Route::get('admin', [AdminController::class, 'getAdmin']); 
            Route::get('getadmin/{id}', [AdminController::class, 'getAdminById']); 
            Route::patch('admin/{idUser}', [AdminController::class, 'updateAdmin']); 
            Route::delete('admin', [AdminController::class, 'deleteAdmin']); 
            Route::get('admin/{idAdmin}', [AdminController::class, 'createAdmin']); 
            Route::post('staff', [StaffController::class, 'createStaff']); 
            Route::delete('staff/{id}', [StaffController::class, 'deleteStaff']); 
            Route::patch('staff/{id}/{type}', [StaffController::class, 'updateTypeStaff']); 
            Route::get('staffs/{adminId}', [StaffController::class, 'getAllStaffs']); 
            Route::get('staffsweb/{adminId}', [StaffController::class, 'getAllStaffsWeb']); 
            Route::get('staffsByUserId/{userId}', [StaffController::class, 'getAllStaffsByUserId']); 
            Route::post('farms', [FarmController::class, 'createFarm']); 
            Route::get('farms/{id}', [FarmController::class, 'getAllFarmsPerAdmin']); 
            Route::patch('farms/{id}', [FarmController::class, 'updateFarm']); 
            Route::delete('farms/{id}', [FarmController::class, 'deleteFarm']); 
            Route::get('getAllFarmsDashboard', [FarmController::class, 'getAllFarmsDashboard']); 
            Route::get('farms/getSingleFarm/{id}', [FarmController::class, 'getSingleFarm']);  
            Route::get('getFarmsWithGreenhouses/{id}', [FarmController::class, 'getFarmsWithGreenhouses']);  
            Route::get('getFarmsWithGreenhousesWithPlaques/{id}/{type}', [FarmController::class, 'getFarmsWithGreenhousesWithPlaques']);  
            Route::post('serres', [SerreController::class, 'createSerre']); 
            Route::get('getNames/{idFarm}/{idSerre}', [FarmController::class, 'getNames']); 
            Route::post('serres2', [SerreController::class, 'createSerre2']); 
            Route::get('serres-per-farm/{farmId}', [SerreController::class, 'getAllSerresPerFarm']); 
            Route::patch('serres/{id}', [SerreController::class, 'updateSerre']); 
            Route::delete('serres/{id}', [SerreController::class, 'deleteSerre']); 
            Route::post('create_prediction', [PredictionController::class, 'createPrediction']);  // pending...
            Route::get('predictions', [PredictionController::class, 'getAllPredictions']); 
            Route::get('singlePrediction/{predId}', [PredictionController::class, 'getSinglePredictions']); 
            Route::get('users/{userId}/predictions', [PredictionController::class, 'getUserPredictions']); 
            Route::get('users/{userId}/predictions/with/images', [PredictionController::class, 'getUserPredictionsWithImages']);
            Route::get('users/{userId}/p_with_image_version_two', [PredictionController::class, 'getUserPredictionsWithImages2']); 
            Route::patch('predictions/{id}', [PredictionController::class, 'updatePrediction']); 
            Route::delete('predictions/{id}', [PredictionController::class, 'deletePrediction']);
            Route::post('images', [ImageController::class, 'createImage']);
            Route::get('predictions/{predictionId}/images', [ImageController::class, 'getPredictionImages']);
            Route::patch('images/{id}', [ImageController::class, 'updateImage']);
            Route::get('getAllImages', [ImageController::class, 'getAllImages']);
            Route::delete('images/{id}', [ImageController::class, 'deleteImage']); 
            Route::post('createPlaque', [PlaqueController::class, 'createPlaque']); 
            Route::get('getAllDemos', [BookdemoController::class, 'getAllDemos']);
            Route::get('markAsDone/{id}', [BookdemoController::class, 'markAsDone']);
            Route::get('markDemandesAsSeen', [UserController::class, 'markDemandesAsSeen']);
            Route::get('markReservationsAsSeen', [BookdemoController::class, 'markReservationsAsSeen']);
    });

    Route::get('userHaveSeenBroadCast/{id}', [UserController::class, 'userHaveSeenBroadCast']); 
    Route::post('refuse/{id}', [UserController::class, 'refuserUser']); 
    Route::post('accept/{id}', [UserController::class, 'accepterUser']); 
    Route::post('updatePassword/{id}', [UserController::class, 'updatePassword']); 
    Route::post('updatePassword2', [UserController::class, 'updatePassword2']); 
    Route::post('password/email', [ForgotPass::class, 'sendResetLinkEmail']);
    Route::post('password/otp', [ForgotPass::class, 'validateOtp']);
    Route::post('book-demo', [BookdemoController::class, 'bookDemoForuser']);

});