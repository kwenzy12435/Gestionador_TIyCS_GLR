<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {

            // ============================================
            // 1. DISPOSITIVOS
            // ============================================
            $totalDispositivos = DB::table('inventario_dispositivos')->count();

            $estadoDispositivos = DB::table('inventario_dispositivos')
                ->select(
                    DB::raw("SUM(estado = 'nuevo') AS nuevos"),
                    DB::raw("SUM(estado = 'asignado') AS asignados"),
                    DB::raw("SUM(estado = 'reparación') AS reparacion"),
                    DB::raw("SUM(estado = 'baja') AS baja")
                )
                ->first();

            // ============================================
            // 2. LICENCIAS
            // ============================================
            $totalLicencias = DB::table('licencias')->count();

            $licenciasEstado = DB::table('licencias')
                ->selectRaw("
                    COUNT(*) AS total,
                    SUM(CASE WHEN expiracion < CURDATE() THEN 1 END) AS expiradas,
                    SUM(CASE WHEN expiracion >= CURDATE() THEN 1 END) AS activas
                ")
                ->first();

            // ============================================
            // 3. USUARIOS TI
            // ============================================
            $totalUsuariosTI = DB::table('usuarios_ti')->count();

            // ============================================
            // 4. COLABORADORES
            // ============================================
            $totalColaboradores = DB::table('colaboradores')->count();

            // ============================================
            // 5. ARTÍCULOS
            // ============================================
            $totalArticulos = DB::table('articulos')->count();

            // ============================================
            // 6. ACTIVIDAD (últimos 7 días)
            // ============================================
            $actividadDias = DB::table('reporte_actividades')
                ->selectRaw("fecha, COUNT(*) AS cantidad")
                ->whereBetween('fecha', [
                    now()->subDays(7)->toDateString(),
                    now()->toDateString()
                ])
                ->groupBy('fecha')
                ->orderBy('fecha')
                ->get();

            // ============================================
            // 7. USUARIOS TI CON MÁS TICKETS (CORRECTO)
            // ============================================
        $usuariosMasTickets = DB::table('reporte_actividades')
    ->join('usuarios_ti', 'reporte_actividades.usuario_ti_id', '=', 'usuarios_ti.id')
    ->select(
        'reporte_actividades.usuario_ti_id',
        DB::raw("CONCAT(usuarios_ti.nombres, ' ', COALESCE(usuarios_ti.apellidos, '')) AS nombre"),
        DB::raw("COUNT(reporte_actividades.id) AS total_tickets")
    )
    ->groupBy('reporte_actividades.usuario_ti_id', 'usuarios_ti.nombres', 'usuarios_ti.apellidos')
    ->orderByDesc('total_tickets')
    ->limit(5)
    ->get();

            return view("dashboard", compact(
                'totalDispositivos',
                'estadoDispositivos',
                'totalLicencias',
                'licenciasEstado',
                'totalUsuariosTI',
                'totalColaboradores',
                'totalArticulos',
                'actividadDias',
                'usuariosMasTickets'
            ));

        } catch (\Exception $e) {

            \Log::error("ERROR EN DASHBOARD: " . $e->getMessage());
            return back()->with("error", "Error en Dashboard");
        }
    }
}
