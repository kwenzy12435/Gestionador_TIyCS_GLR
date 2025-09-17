<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioTIController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ColaboradorController;
use App\Http\Controllers\InventarioDispositivoController;
use App\Http\Controllers\LicenciaController;
use App\Http\Controllers\ReporteActividadController;
use App\Http\Controllers\BitacoraRespaldoController;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\MonitoreoRedController;

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
// Rutas para inventario de colaboradores
Route::resource('colaboradores', ColaboradorController::class)
    ->names('colaboradores')
    ->parameters(['colaboradores' => 'id']);

// Rutas para inventario de dispositivos
Route::resource('inventario-dispositivos', InventarioDispositivoController::class)
    ->parameters(['inventario-dispositivos' => 'id'])
    ->names('inventario-dispositivos');

// Rutas para gestión de licencias
Route::resource('licencias', LicenciaController::class)
    ->parameters(['licencias' => 'id'])
    ->names('licencias');

// Rutas para reporte de actividades
    Route::resource('reporte_actividades', ReporteActividadController::class)
    ->parameters(['reporte_actividades' => 'id'])
    ->names('reporte_actividades');

// Rutas para bitacora respaldos contpaq
Route::resource('bitacora_respaldo', BitacoraRespaldoController::class)
     ->names('bitacora_respaldo');

// Rutas para articulos

Route::resource('articulos', ArticuloController::class);
 
// Rutas para monitoreo de red
  Route::resource('monitoreo-red', MonitoreoRedController::class);
});