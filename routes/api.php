<?php

Route::prefix('version1/auth')->group(function () {
    Route::post('login', 'Api\AuthController@login');
    Route::post('mobile/login', 'Api\AuthController@mobileLogin');
    Route::post('signup', 'Api\AuthController@signup');
    Route::post('signup/via/phone', 'Api\AuthController@signupViaPhone');
    Route::post('social-login', 'Api\AuthController@socialLogin');
    Route::post('password/create', 'Api\PasswordResetController@create');
    Route::post('otp/send', 'Api\OtpVerificationController@OtpSend');
    Route::post('otp/checked', 'Api\OtpVerificationController@OtpCheck');
    Route::middleware('auth:api')->group(function () {
        Route::get('logout', 'Api\AuthController@logout');
        Route::get('user', 'Api\AuthController@user');
        Route::post('change-password', 'Api\AuthController@changePass');
    });
});

Route::prefix('version1')->group(function () {
    Route::apiResource('business-settings', 'Api\BusinessSettingController')->only('index');
    Route::post('settings/update/{id}', 'Api\SettingsController@updateName');
    Route::apiResource('settings', 'Api\SettingsController')->only('index');
    Route::get('user/info/{id}', 'Api\UserController@info')->middleware('auth:api');
    Route::post('user/info/update', 'Api\UserController@updateName')->middleware('auth:api');
    Route::post('user/information', 'Api\UserInformationController@store')->middleware('auth:api');
    Route::get('user/information/{id}', 'Api\UserInformationController@information')->middleware('auth:api');
});

Route::fallback(function() {
    return response()->json([
        'data' => [],
        'success' => false,
        'status' => 404,
        'message' => 'Invalid Route'
    ]);
});
