<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\JurnalController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

route::post("auth/register", [UserController::class, "register"]);
route::post("auth/login", [UserController::class, "login"]);
route::post("auth/logout", [UserController::class, "logout"]);
route::post("auth/update/{nip}", [UserController::class, "update"]);
route::get("auth/checktoken", [UserController::class, "checkToken"]);

route::get("jurnal/index/{nip}/{bulan}/{tahun}", [JurnalController::class, "index"]);
route::post("jurnal/store", [JurnalController::class, "store"]);
route::delete("jurnal/destroy/{id}", [JurnalController::class, "destroy"]);
route::post("jurnal/update/{id}", [JurnalController::class, "update"]);