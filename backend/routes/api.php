<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\Api\LivreurController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\ZoneController;
use App\Http\Controllers\Api\VehiculeController;
use App\Http\Controllers\Api\RapportController;

/*
|--------------------------------------------------------------------------
| Routes API - FloLiv
|--------------------------------------------------------------------------
*/

// Route de test (utilisateur connecté)
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// --- Connexion ---
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Identifiants invalides.'], 401);
    }

    return response()->json([
        'token' => $user->createToken('floliv')->plainTextToken
    ]);
});

// --- Mot de passe oublié ---
Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return response()->json([
        'message' => __($status)
    ], $status === Password::RESET_LINK_SENT ? 200 : 400);
})->name('password.email');

// --- Réinitialisation du mot de passe ---
Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->save();
        }
    );

    return response()->json([
        'message' => __($status)
    ], $status === Password::PASSWORD_RESET ? 200 : 400);
})->name('password.reset');

/*
|--------------------------------------------------------------------------
| Routes métier (protégées par Sanctum)
|--------------------------------------------------------------------------
*/
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