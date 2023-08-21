<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;

Route::apiResource('channels', ChannelController::class);
Route::apiResource('commands', CommandController::class);
Route::post('identities/verifications', VerifyIdentityController::class);
Route::apiResource('images', ImageController::class);
Route::post('recoveries', ForgotPasswordController::class);
Route::post('recoveries/verifications', VerifyRecoveryController::class);
Route::get('social-media-providers', [ SocialMediaProviderController::class, 'index' ]);
Route::apiResource('sounds', SoundController::class);
Route::post('tokens', LoginController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('videos', VideoController::class);
