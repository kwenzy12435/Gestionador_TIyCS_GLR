<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\AuthController;

// Redirección principal al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas públicas
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Rutas para servir archivos estáticos desde resources (DEBEN estar FUERA del middleware)
Route::get('/css/{filename}', function ($filename) {
    if (!preg_match('/\.css$/', $filename)) {
        abort(404);
    }

    $path = resource_path('css/' . $filename);
    
    if (!File::exists($path)) {
        abort(404, "Archivo CSS no encontrado: " . $filename);
    }

    return response()
        ->file($path, ['Content-Type' => 'text/css']);
})->where('filename', '.*');

Route::get('/js/{filename}', function ($filename) {
    if (!preg_match('/\.js$/', $filename)) {
        abort(404);
    }

    $path = resource_path('js/' . $filename);
    
    if (!File::exists($path)) {
        abort(404, "Archivo JS no encontrado: " . $filename);
    }

    return response()
        ->file($path, ['Content-Type' => 'application/javascript']);
})->where('filename', '.*');

// Rutas protegidas (verificación manual en controlador)
Route::middleware(['web'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Ejemplo de otras rutas protegidas
    Route::get('/profile', function () {
        if (!session('usuario_ti_id')) {
            return redirect()->route('login');
        }
        return view('profile');
    })->name('profile');
});