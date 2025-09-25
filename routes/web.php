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
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\LogBajasController;




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

// Rutas para QR
Route::get('/inventario-dispositivos/{id}/qr', [InventarioDispositivoController::class, 'generarQR'])->name('inventario-dispositivos.qr.descargar');
Route::get('/inventario-dispositivos/{id}/qr-imprimible', [InventarioDispositivoController::class, 'qrImprimible'])->name('inventario-dispositivos.qr.imprimible');

// Rutas para inventario de dispositivos
Route::resource('inventario-dispositivos', InventarioDispositivoController::class)
    ->parameters(['inventario-dispositivos' => 'id'])
    ->names('inventario-dispositivos');
    

// Rutas para licencias
Route::resource('licencias', LicenciaController::class);
Route::post('/confirmar-password', [LicenciaController::class, 'confirmarPassword'])->name('confirmar-password');

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

  // rutas para configuración del sistema
Route::prefix('admin/configsistem')->group(function () {
    Route::get('/', [ConfigSistemaController::class, 'index'])->name('admin.configsistem.index');
    Route::get('/{tabla}', [ConfigSistemaController::class, 'index'])->name('admin.configsistem.index.tabla');
    Route::post('/{tabla}', [ConfigSistemaController::class, 'store'])->name('admin.configsistem.store');
    Route::put('/{tabla}/{id}', [ConfigSistemaController::class, 'update'])->name('admin.configsistem.update');
    Route::delete('/{tabla}/{id}', [ConfigSistemaController::class, 'destroy'])->name('admin.configsistem.destroy');
});
// Rutas para el historial de bajas
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/bajas', [LogBajasController::class, 'index'])->name('admin.bajas.index');
    Route::get('/bajas/buscar', [LogBajasController::class, 'search'])->name('admin.bajas.search');
    Route::get('/bajas/{id}', [LogBajasController::class, 'show'])->name('admin.bajas.show');
    Route::get('/bajas/exportar/pdf', [LogBajasController::class, 'exportPdf'])->name('admin.bajas.export.pdf');
});


});