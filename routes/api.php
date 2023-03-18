<?php

use App\Http\Controllers\LoanController;
use Illuminate\Http\Request;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(static function () {
    Route::middleware('can:loan.create')
        ->post('loans', [LoanController::class, 'store'])
        ->name('loans.store');

    Route::middleware(['can:view,loan'])
        ->get('loans/{loan}', [LoanController::class, 'view'])
        ->name('loans.view');

    Route::middleware(['can:view,loan'])
        ->post('loans/{loan}/make-payment', [LoanController::class, 'makePayment'])
        ->name('loans.make_payment');

    Route::middleware('can:loan.updateStatus')
        ->post('loans/{id}/update-status', [LoanController::class, 'updateStatus'])
        ->name('loans.update_status');
});
