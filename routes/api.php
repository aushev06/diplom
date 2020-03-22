<?php

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

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

Route::middleware('auth:airlock')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/airlock/token', function (Request $request) {
    $request->validate([
        'email'       => 'required|email',
        'password'    => 'required',
        'device_name' => 'required'
    ]);


    $user = User::where('email', $request->email)->first();

    if (null === $user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'The provided credentials are incorrect.',
        ], 401);
    }
    $token = $user->createToken($request->device_name)->plainTextToken;
    return response()->json([
        'user'  => $user,
        'token' => $token,
    ]);
});


Route::middleware('auth:airlock')->post('/logout', function (Request $request) {
    $request->user()->tokens()->delete();

    return response('Loggedout', 200);
});

Route::group(['middleware' => 'auth:airlock'], function () {
    Route::get('/clients/list', 'ClientController@listClients');
    Route::get('/foods/list', 'FoodController@foodList');
    Route::resources([
        'clients' => 'ClientController',
        'foods'   => 'FoodController',
        'orders'  => 'OrderController',
    ]);

    Route::get('/stats', 'OrderController@stats');
});
