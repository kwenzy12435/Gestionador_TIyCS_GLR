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
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\AdminMiddleware; 
// ========================
// RUTAS PÚBLICAS / LOGIN
// ========================

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// ========================
// RUTAS PROTEGIDAS (AUTH)
// ========================

Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', function () {
        return redirect()->route('usuarios-ti.edit', auth()->id());
    })->name('profile.edit');

    Route::put('/usuarios-ti/actualizar-credenciales', [UsuarioTIController::class, 'actualizarCredenciales'])
        ->name('usuarios-ti.actualizar-credenciales');

    // ========================
    // USUARIOS TI (solo admin)
    // ========================
   Route::middleware(AdminMiddleware::class)->group(function () {
    Route::resource('usuarios-ti', UsuarioTIController::class)
        ->parameters(['usuarios-ti' => 'usuarioTi'])
        ->names('usuarios-ti');
});

    // ========================
    // REPORTES DE ACTIVIDAD
    // ========================
    Route::resource('reporte_actividades', ReporteActividadController::class)
        ->parameters(['reporte-actividades' => 'reporteActividad'])
        ->names('reporte_actividades');

    // ========================
    // MONITOREO DE RED
    // ========================
    Route::resource('monitoreo_red', MonitoreoRedController::class)
        ->parameters(['monitoreo_red' => 'monitoreoRed'])
        ->names('monitoreo_red');

    // ========================
    // LOG DE BAJAS (solo admin)
    // ========================
  Route::middleware(AdminMiddleware::class)
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        Route::get('bajas', [LogBajasController::class, 'index'])->name('bajas.index');
        Route::get('bajas/search', [LogBajasController::class, 'search'])->name('bajas.search');
        Route::get('bajas/export/pdf', [LogBajasController::class, 'exportPdf'])->name('bajas.export.pdf');
        Route::get('bajas/{id}', [LogBajasController::class, 'show'])->name('bajas.show');
    });
    
// ========================
// LICENCIAS
// ========================
Route::prefix('licencias')->name('licencias.')->group(function () {
    // Rutas resource principales
    Route::get('/', [LicenciaController::class, 'index'])->name('index');
    Route::get('/create', [LicenciaController::class, 'create'])->name('create');
    Route::post('/', [LicenciaController::class, 'store'])->name('store');
    Route::get('/{licencia}', [LicenciaController::class, 'show'])->name('show');
    Route::get('/{licencia}/edit', [LicenciaController::class, 'edit'])->name('edit');
    Route::put('/{licencia}', [LicenciaController::class, 'update'])->name('update');
    Route::delete('/{licencia}', [LicenciaController::class, 'destroy'])->name('destroy');
    
    // Rutas adicionales
    Route::get('/por-expiar', [LicenciaController::class, 'licenciasPorExpiar'])->name('por-expiar');
    
    // Rutas para ver contraseña - CORREGIDO
    Route::get('/{licencia}/ver-contrasena', [LicenciaController::class, 'verContrasena'])->name('ver-contrasena');
    Route::post('/{licencia}/ver-contrasena', [LicenciaController::class, 'procesarVerContrasena'])->name('procesar-ver-contrasena');
    
    Route::post('/confirmar-password', [LicenciaController::class, 'confirmarPassword'])->name('confirmar-password');
});
    // ========================
    // INVENTARIO DISPOSITIVOS
    // ========================
    Route::resource('inventario-dispositivos', InventarioDispositivoController::class)
        ->parameters(['inventario-dispositivos' => 'inventarioDispositivo'])
        ->names('inventario-dispositivos');

    Route::get('inventario-dispositivos/{inventarioDispositivo}/qr', 
        [InventarioDispositivoController::class, 'generarQR'])
        ->name('inventario-dispositivos.qr');

    // ========================
    // CONFIGURACIÓN DEL SISTEMA (solo admin)
    // ========================
Route::middleware(AdminMiddleware::class)
    ->prefix('admin/configsistem')
    ->as('admin.configsistem.')
    ->group(function () {
        Route::get('/{tabla?}', [ConfigSistemaController::class, 'index'])
            ->where('tabla', '.*')->name('index');
        Route::post('/{tabla}', [ConfigSistemaController::class, 'store'])->name('store');
        Route::put('/{tabla}/{id}', [ConfigSistemaController::class, 'update'])
            ->whereNumber('id')->name('update');
        Route::delete('/{tabla}/{id}', [ConfigSistemaController::class, 'destroy'])
            ->whereNumber('id')->name('destroy');
    });
    // ========================
    // COLABORADORES
    // ========================
    Route::resource('colaboradores', ColaboradorController::class)
        ->parameters(['colaboradores' => 'colaborador'])
        ->names('colaboradores');

    // ========================
    // BITÁCORA DE RESPALDO
    // ========================
    Route::resource('bitacora-respaldo', BitacoraRespaldoController::class)
        ->parameters(['bitacora-respaldo' => 'bitacoraRespaldo'])
        ->names([
            'index' => 'bitacora-respaldo.index',
            'create' => 'bitacora-respaldo.create', 
            'store' => 'bitacora-respaldo.store',
            'show' => 'bitacora-respaldo.show',
            'edit' => 'bitacora-respaldo.edit',
            'update' => 'bitacora-respaldo.update',
            'destroy' => 'bitacora-respaldo.destroy'
        ]);

    // ========================
    // ARTÍCULOS
    // ========================
    Route::resource('articulos', ArticuloController::class)
        ->parameters(['articulos' => 'articulo'])
        ->names('articulos');
        
        // SOLO para probar y luego borras
Route::get('/_t404', fn() => abort(404));
Route::get('/_t403', fn() => abort(403));

});