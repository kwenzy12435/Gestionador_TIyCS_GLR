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
use App\Http\Controllers\ConfigSistemaController;
use App\Http\Controllers\LogBajasController;

// Redirección principal DIRECTA al login
Route::get('/', function () {
    return redirect('/login');
});
Route::post('login', [Auth\LoginController::class, 'login'])
    ->middleware('throttle:login');

// Rutas de autenticación (públicas)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Perfil de usuario
    Route::get('/profile', function () {
        return redirect()->route('usuarios-ti.edit', auth()->id());
    })->name('profile.edit');

    // Actualización de credenciales
    Route::put('/usuarios-ti/actualizar-credenciales', [UsuarioTIController::class, 'actualizarCredenciales'])
        ->name('usuarios-ti.actualizar-credenciales');

    // CRUD de Usuarios TI
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('usuarios-ti', UsuarioTIController::class);
});
// CRUD de Colaboradores
Route::middleware(['auth'])->group(function () {
    Route::resource('reporte_actividades', ReporteActividadController::class);
});
//CRUD DE MONITOREO DE RED
Route::middleware(['auth'])->group(function () {
    Route::resource('monitoreo-red', MonitoreoRedController::class);
});
//log bajas
Route::middleware(['auth', 'admin']) // <-- tu middleware de rol ADMIN
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        Route::get('bajas', [LogBajasController::class, 'index'])->name('bajas.index');
        Route::get('bajas/search', [LogBajasController::class, 'search'])->name('bajas.search'); // opcional (index ya delega a search)
        Route::get('bajas/export/pdf', [LogBajasController::class, 'exportPdf'])->name('bajas.export.pdf');
        Route::get('bajas/{id}', [LogBajasController::class, 'show'])->name('bajas.show');
    });
    //licencias 
    Route::middleware(['auth'])->group(function () {
    Route::resource('licencias', LicenciaController::class);

    // Extras
    Route::get('licencias/por-expiar', [LicenciaController::class, 'licenciasPorExpiar'])
        ->name('licencias.por_expiar');

    // Revelar contraseña (AJAX)
    Route::post('licencias/{id}/revelar', [LicenciaController::class, 'revelarContrasena'])
        ->name('licencias.revelar');

    // Flujo alterno por vista
    Route::get('licencias/{id}/ver-contrasena', [LicenciaController::class, 'verContrasena'])
        ->name('licencias.ver_contrasena');
    Route::post('licencias/{id}/ver-contrasena', [LicenciaController::class, 'procesarVerContrasena'])
        ->name('licencias.procesar_ver_contrasena');

    // (opcional) endpoint genérico para confirmar password (no lo usamos en JS, ya que revelarContrasena valida)
    Route::post('licencias/confirmar-password', [LicenciaController::class, 'confirmarPassword'])
        ->name('licencias.confirmar_password');
});
});