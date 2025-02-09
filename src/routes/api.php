<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;

use App\Http\Controllers\API\V1\AI\AnalysisController;
use App\Http\Controllers\API\V1\CategoriesController;
use App\Http\Controllers\API\V1\ChatBotController;
use App\Http\Controllers\API\V1\Messages\ChatMessageController;
use App\Http\Controllers\API\V1\Employee\EmployeesController;
use App\Http\Controllers\API\V1\ImpersonateController;
use App\Http\Controllers\API\V1\AI\KerasController;
use App\Http\Controllers\API\V1\AI\PredictionController;
use App\Http\Controllers\API\V1\OrganizationsController;
use App\Http\Controllers\API\V1\ProductsController;
use App\Http\Controllers\API\V1\System\SystemsController;
use App\Http\Controllers\API\V1\System\SystemsHelperController;
use App\Http\Controllers\API\V1\User\UsersController;
use App\Http\Controllers\API\V1\User\UserTypesHelperController;
use App\Http\Controllers\API\V1\User\UsersHelperController;
use App\Http\Controllers\Auth\AuthController;


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return response()->json(['user' => $request->user()]);
    });

    Route::prefix('v1')->group(function () {

        Route::prefix('analysis')->group(function () {
            Route::apiResource('/', AnalysisController::class);
        });

        Route::prefix('categories')->group(function () {
            Route::apiResource('/', CategoriesController::class);
        });

        Route::prefix('chat-messages')->group(function () {
            Route::apiResource('/', ChatMessageController::class)->middleware('auth:sanctum');
            Route::get('/{friend}', [ChatMessageController::class, 'getChatMessages'])->middleware('auth:sanctum');
        });


        Route::prefix('employees')->group(function () {
            Route::apiResource('/', EmployeesController::class);
        });

        Route::prefix('keras')->group(function () {
            Route::apiResource('/', KerasController::class);
            Route::post('/predict', [KerasController::class, 'predict']);
        });

        Route::prefix('organizations')->group(function () {
            Route::apiResource('/', OrganizationsController::class);
        });

        Route::prefix('prediction')->group(function () {
            Route::post('/predict', [PredictionController::class, 'predict']);
            Route::post('/train', [PredictionController::class, 'train']);
        });

        Route::prefix('products')->group(function () {
            Route::apiResource('/', ProductsController::class);
        });

        Route::prefix('systems')->group(function () {
            Route::apiResource('/', SystemsController::class);
            Route::get('/active-accesses', [SystemsHelperController::class, 'getSystemActiveAccesses']);
        });

        Route::prefix('users')->group(function () {
            Route::apiResource('/', UsersController::class);
            Route::get('/types/{attribute}/{value}', [UserTypesHelperController::class, 'getUserTypes']);
            Route::get('/{option}', [UsersController::class, 'show']);
            Route::put('/update-accesses', [UsersHelperController::class, 'updateAccesses']);

        });
      
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/verify-token', [AuthController::class, 'verifyToken']);

    });
});

Route::prefix('v1')->group(function () {     
    //Route::get('/login/{employee_no}', [AuthController::class, 'login']);

    /** Login thru submit */
    Route::post('/login', [AuthController::class, 'login']);


    Route::get('/products', [ProductsController::class, 'index']);
   
    Route::impersonate();
    Route::post('/impersonate', [ImpersonateController::class, 'index']);

    Route::prefix('approvals')->group(function () {
      
    });
  
});