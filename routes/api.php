<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

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

Route::get('/', function () {
    return ['status' => 'OK', 'message' => 'You are at home!'];
});

Route::get('/auth/redirect', function () {
    return Socialite::driver('facebook')->stateless()->redirect();
});

Route::get('/auth/facebook/callback', function () {
    $user = Socialite::driver('facebook')->stateless()->user();

    dd($user);
});
