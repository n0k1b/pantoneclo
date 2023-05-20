<?php

use App\Http\Controllers\API\ClientAutoUpdateController;
use App\Http\Controllers\API\DemoAutoUpdateController;
use App\Http\Controllers\API\Frontend\CommonController;
use App\Http\Controllers\Frontend\CartController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Fetch data from Demo
Route::get('fetch-data-general', [DemoAutoUpdateController::class, 'fetchDataGeneral'])->name('fetch-data-general');
Route::get('fetch-data-upgrade', [DemoAutoUpdateController::class, 'fetchDataForAutoUpgrade'])->name('data-read');
Route::get('fetch-data-bugs', [DemoAutoUpdateController::class, 'fetchDataForBugs'])->name('fetch-data-bugs');

// Action in Client server
Route::post('bug-update', [ClientAutoUpdateController::class, 'bugUpdate'])->name('bug-update');
Route::post('version-upgrade', [ClientAutoUpdateController::class, 'versionUpgrade'])->name('version-upgrade');


/*
|--------------------------------------------------------------------------
| Frontend React
|--------------------------------------------------------------------------
*/
Route::get('common-data', [CommonController::class, 'index']);
Route::get('header-data', [CommonController::class, 'headerData']);
Route::post('contact', [CommonController::class, 'contact']);
Route::post('send-email', [CartController::class, 'sendEmail'])->name('api.send-email');
