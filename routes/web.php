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
Route::resource('usuarios-ti', UsuarioTIController::class)->parameters([
    'usuarios-ti' => 'usuarios_ti'
]);
    
    // CRUD de Colaboradores
    Route::resource('colaboradores', ColaboradorController::class)
        ->names('colaboradores')
        ->parameters(['colaboradores' => 'id']);

    // CRUD de Inventario de Dispositivos
    Route::resource('inventario-dispositivos', InventarioDispositivoController::class)
        ->parameters(['inventario-dispositivos' => 'id'])
        ->names('inventario-dispositivos');

    // Generar QR para dispositivos
    Route::get('inventario-dispositivos/{id}/qr', [InventarioDispositivoController::class, 'generarQR'])
        ->name('inventario-dispositivos.qr.descargar');

    // CRUD de Licencias con rutas adicionales de seguridad
    Route::resource('licencias', LicenciaController::class);
    
    // Rutas de confirmación de contraseña para licencias
    Route::post('/licencias/confirmar-password', [LicenciaController::class, 'confirmarPassword'])
        ->name('licencias.confirmar-password')
        ->middleware('throttle:10,1');
    
    // Rutas para visualización de contraseñas de licencias
    Route::get('licencias/{id}/ver-contrasena', [LicenciaController::class, 'verContrasena'])
        ->name('licencias.ver-contrasena');
    
    Route::post('licencias/{id}/ver-contrasena', [LicenciaController::class, 'procesarVerContrasena'])
        ->name('licencias.procesar-ver-contrasena');

    Route::post('licencias/{id}/revelar-contrasena', [LicenciaController::class, 'revelarContrasena'])
        ->name('licencias.revelar-contrasena');

    // CRUD de Reportes de Actividades
    Route::resource('reporte_actividades', ReporteActividadController::class)
        ->parameters(['reporte_actividades' => 'id'])
        ->names('reporte_actividades');

    // CRUD de Bitácora de Respaldos
    Route::resource('bitacora_respaldo', BitacoraRespaldoController::class)
        ->names('bitacora_respaldo');

    // CRUD de Artículos
    Route::resource('articulos', ArticuloController::class);

    // CRUD de Monitoreo de Red
    Route::resource('monitoreo-red', MonitoreoRedController::class);

    // Configuración del Sistema (Admin)
   Route::prefix('admin/configsistem')->group(function () {
    Route::get('/', [ConfigSistemaController::class, 'index'])->name('admin.configsistem.index');
    Route::get('/{tabla}', [ConfigSistemaController::class, 'index'])->name('admin.configsistem.index.tabla');
    Route::post('/{tabla}', [ConfigSistemaController::class, 'store'])->name('admin.configsistem.store');
    Route::put('/{tabla}/{id}', [ConfigSistemaController::class, 'update'])->name('admin.configsistem.update');
    Route::delete('/{tabla}/{id}', [ConfigSistemaController::class, 'destroy'])->name('admin.configsistem.destroy');
})->middleware('auth');
    // Historial de Bajas (Admin)
    Route::prefix('admin')->group(function () {
        Route::get('/bajas', [LogBajasController::class, 'index'])->name('admin.bajas.index');
        Route::get('/bajas/buscar', [LogBajasController::class, 'search'])->name('admin.bajas.search');
        Route::get('/bajas/{id}', [LogBajasController::class, 'show'])->name('admin.bajas.show');
        Route::get('/bajas/exportar/pdf', [LogBajasController::class, 'exportPdf'])->name('admin.bajas.export.pdf');
    });

});