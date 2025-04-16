<?php

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
    });
});

Route::group(['prefix' => 'users', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [UserController::class, 'get']);
    Route::get('/leaders', [UserController::class, 'getLeaders']);
    Route::get('/agents', [UserController::class, 'getAgents']);
    Route::get('/{id}', [UserController::class, 'show']);

    Route::post('/', [UserController::class, 'store']);
    Route::put('/', [UserController::class, 'update']);
    Route::post('/update-password', [UserController::class, 'updatePassword']);
    Route::put('/activate/{id}', [UserController::class, 'activate']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
    Route::put('/{id}/reset-password', [UserController::class, 'resetPassword']);
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth:sanctum'], function () {

    Route::group(['prefix' => 'campaign-categories'], function () {
        Route::get('/', [AdminController::class, 'getAllCampaignCategories']);

        Route::post('/', [AdminController::class, 'createCampaignCategory']);
        Route::put('/{id}', [AdminController::class, 'updateCampaignCategory']);
        Route::delete('/{id}', [AdminController::class, 'deleteCampaignCategory']);
    });

    Route::group(['prefix' => 'progress-categories'], function () {
        Route::get('/', [AdminController::class, 'getAllProgressCategories']);

        Route::post('/', [AdminController::class, 'createProgressCategory']);
        Route::put('/{id}', [AdminController::class, 'updateProgressCategory']);
        Route::delete('/{id}', [AdminController::class, 'deleteProgressCategory']);
    });

    Route::group(['prefix' => 'campaigns'], function () {
        Route::get('/', [CampaignController::class, 'getAllCampaigns']);
        Route::get('/racenames', [CampaignController::class, 'getRacenames']);

        Route::post('/', [CampaignController::class, 'createCampaign']);
        Route::put('/', [CampaignController::class, 'updateCampaign']);
        Route::put('/activate/{id}', [CampaignController::class, 'activateCampaign']);
        Route::delete('/{id}', [CampaignController::class, 'deleteCampaign']);

        Route::get('/{id}/count-unassigned', [CampaignController::class, 'getUnassignedCount']);
        Route::post('/{id}/assign', [CampaignController::class, 'assign']);

        Route::get('/{id}/count-unassigned-filter', [CampaignController::class, 'getUnassignedCountWithFilter']);
        Route::post('/{id}/assign-filter', [CampaignController::class, 'assignWithFilter']);

        Route::get('/{id}/filters', [CampaignController::class, 'getCampaignDetailFilters']);
        Route::post('/{id}/list', [CampaignController::class, 'getCampaignDetailList']);
        Route::post('/{id}/import', [CampaignController::class, 'importCampaignDetail']);
    });
});

Route::group(['prefix' => 'campaign-detail', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/{id}/total-filtered-count', [CampaignController::class, 'getTotalFilteredCount']);
    Route::put('/{id}/status', [CampaignController::class, 'updateCampaignDetailStatus']);
    Route::put('/{id}/ref-number', [CampaignController::class, 'updateCampaignDetailRefNumber']);
    Route::post('/{id}/lead-progress', [CampaignController::class, 'getLeadProgress']);
    Route::get('/{id}', [CampaignController::class, 'getCampaignDetailInfo']);
});

Route::middleware('auth:sanctum')->get('/update-password', '');

Route::get('/placeholder-pwd', [AuthController::class, 'genPassword']);
