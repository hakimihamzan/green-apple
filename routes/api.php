<?php

use App\Models\FacebookDetail;
use App\Models\User;
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

Route::get('/', function () {
    return ['status' => 'OK', 'message' => 'You are at home!'];
});

Route::get('/auth/{provider}/redirect', function ($provider) {
    return Socialite::driver($provider)->stateless()->redirect();
});

Route::get('/auth/{provider}/callback', function ($provider) {
    $socialDetails = Socialite::driver($provider)->stateless()->user();

    // Register new user
    $newUser = User::create([
        'name' => $socialDetails->name,
        'email' => $socialDetails->email,
        'password' => "password",
    ]);

    // $socialData = null;
    if ($provider == 'facebook') {
        // Save facebook data to DB
        $socialData = FacebookDetail::create([
            'name' => $socialDetails->name ?? '',
            'nickname' => $socialDetails->nickname ?? '',
            'facebook_id' => $socialDetails->id,
            'email' => $socialDetails->email ?? '',
            'avatar_url' => $socialDetails->avatar ?? '',
            'user_id' => $newUser->id,
        ]);
    }

    // if ($provider == 'google') {
    //     // Save google data to DB
    //     $socialData = GoogleDetail::create([
    //         'name' => $socialDetails->name ?? '',
    //         'nickname' => $socialDetails->nickname ?? '',
    //         'facebook_id' => $socialDetails->id,
    //         'email' => $socialDetails->email ?? '',
    //         'avatar_url' => $socialDetails->avatar ?? '',
    //         'user_id' => $newUser->id,
    //     ]);
    // }

    // Give token
    $token = $newUser->createToken($newUser->email)->plainTextToken;

    return response([
        'user' => $newUser,
        'social_data' => $socialData,
        'token' => $token,
    ], 201);
});
