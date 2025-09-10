<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioTIController;
use App\Http\Controllers\Auth\LoginController;

// Redirección principal al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    

    // Rutas para gestión de usuarios TI
    Route::resource('usuarios-ti', UsuarioTIController::class)->names([
    'index' => 'usuarios-ti.index',
    'create' => 'usuarios-ti.create',
    'store' => 'usuarios-ti.store',
    'show' => 'usuarios-ti.show',
    'edit' => 'usuarios-ti.edit',
    'update' => 'usuarios-ti.update',
    'destroy' => 'usuarios-ti.destroy',
]);
    // Aquí puedes agregar más rutas protegidas
    // Route::resource('inventario', InventarioController::class);
    // Route::resource('tickets', TicketController::class);
    // etc.
});