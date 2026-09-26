<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LivreurController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\ZoneController;
use App\Http\Controllers\Api\VehiculeController;
use App\Http\Controllers\Api\RapportController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', function (Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email', 'password' => 'required']);
    $user = App\Models\User::where('email', $request->email)->first();
    if (!$user || !Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Identifiants invalides.'], 401);
    }
    return response()->json(['token' => $user->createToken('postman')->plainTextToken]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('livreurs', LivreurController::class);
    Route::apiResource('courses', CourseController::class);
    Route::patch('courses/{course}/statut', [CourseController::class, 'changerStatut']);
    Route::patch('courses/{course}/annuler', [CourseController::class, 'annuler']);
    Route::patch('courses/{course}/affecter', [CourseController::class, 'affecter']);
    Route::apiResource('zones', ZoneController::class);
    Route::apiResource('vehicules', VehiculeController::class);
    Route::get('rapports/chiffre-affaires', [RapportController::class, 'chiffreAffaires']);
    Route::get('rapports/export', [RapportController::class, 'export']);
});