<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LogBajasController extends Controller
{
    /**
     * Tamaño de página por defecto
     */
    private const PAGE_SIZE = 20;

    /**
     * Verificar que el usuario sea ADMIN
     */
    private function verificarAdmin(): void
    {
        if (!Auth::check() || Auth::user()->rol !== 'ADMIN') {
            abort(403, 'Acceso no autorizado. Solo administradores pueden ver esta sección.');
        }
    }

    /**
     * Listado base: delega a search() para tener un único flujo
     */
    public function index(Request $request)
    {
        $this->verificarAdmin();
        return $this->search($request);
    }

    /**
     * Mostrar detalles de una baja específica
     */
    public function show($id)
    {
        $this->verificarAdmin();

        $baja = DB::table('log_bajas')
            ->leftJoin('usuarios_ti', 'log_bajas.usuario_ti_id', '=', 'usuarios_ti.id')
            ->leftJoin('marcas', 'log_bajas.marca_id', '=', 'marcas.id')
            ->where('log_bajas.id', $id)
            ->select(
                'log_bajas.*',
                'usuarios_ti.usuario as ti_usuario',
                'usuarios_ti.nombres as ti_nombres',
                'usuarios_ti.apellidos as ti_apellidos',
                'usuarios_ti.puesto as ti_puesto',
                'usuarios_ti.telefono as ti_telefono',
                'marcas.nombre as marca_nombre'
            )
            ->first();

        if (!$baja) {
            abort(404, 'Registro de baja no encontrado');
        }

        return view('admin.bajas.show', compact('baja'));
    }

    /**
     * Buscar / listar bajas con filtros unificados
     */
    public function search(Request $request)
    {
        $this->verificarAdmin();

        // Normalizar entrada
        $request->merge([
            'search'       => trim((string) $request->input('search', '')),
            'fecha_desde'  => $request->input('fecha_desde') ?: null,
            'fecha_hasta'  => $request->input('fecha_hasta') ?: null,
        ]);

        $query = DB::table('log_bajas')
            ->leftJoin('usuarios_ti', 'log_bajas.usuario_ti_id', '=', 'usuarios_ti.id')
            ->leftJoin('marcas', 'log_bajas.marca_id', '=', 'marcas.id')
            ->select(
                'log_bajas.*',
                'usuarios_ti.usuario as ti_usuario',
                'usuarios_ti.nombres as ti_nombres',
                'usuarios_ti.apellidos as ti_apellidos',
                'usuarios_ti.puesto as ti_puesto',
                'marcas.nombre as marca_nombre'
            );

        // Texto libre
        if ($request->filled('search')) {
            $s = $request->input('search');

            // Evitar comodines raros que rompan el LIKE
            $sLike = str_replace(['%', '_'], ['\%', '\_'], $s);

            // Búsqueda usando SQL directo con LIKE para mejor rendimiento
            $query->whereRaw("
                log_bajas.modelo LIKE ? OR 
                log_bajas.numero_serie LIKE ? OR 
                log_bajas.usuario_nombre LIKE ? OR 
                log_bajas.mac_address LIKE ? OR 
                log_bajas.registro_id LIKE ? OR
                log_bajas.tipo LIKE ? OR
                log_bajas.razon_baja LIKE ? OR
                log_bajas.observaciones LIKE ? OR
                marcas.nombre LIKE ? OR 
                usuarios_ti.usuario LIKE ? OR 
                usuarios_ti.nombres LIKE ? OR 
                usuarios_ti.apellidos LIKE ?
            ", array_fill(0, 13, "%{$sLike}%"));
        }

        // Rango de fechas
        if ($request->filled('fecha_desde')) {
            $query->whereDate('log_bajas.fecha', '>=', $request->input('fecha_desde'));
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('log_bajas.fecha', '<=', $request->input('fecha_hasta'));
        }

        // Orden y paginación
        $bajas = $query
            ->orderBy('log_bajas.fecha', 'desc')
            ->paginate(self::PAGE_SIZE)
            ->appends($request->query());

        return view('admin.bajas.index', compact('bajas'));
    }

    /**
     * Exportar bajas a PDF
     */
    public function exportPdf(Request $request)
    {
        $this->verificarAdmin();

        $query = DB::table('log_bajas')
            ->leftJoin('usuarios_ti', 'log_bajas.usuario_ti_id', '=', 'usuarios_ti.id')
            ->leftJoin('marcas', 'log_bajas.marca_id', '=', 'marcas.id')
            ->select(
                'log_bajas.*',
                'usuarios_ti.nombres as ti_nombres',
                'usuarios_ti.apellidos as ti_apellidos',
                'marcas.nombre as marca_nombre'
            );

        // Aplicar mismos filtros de búsqueda al exportar PDF
        if ($request->filled('search')) {
            $s = $request->input('search');
            $sLike = str_replace(['%', '_'], ['\%', '\_'], $s);

            $query->whereRaw("
                log_bajas.modelo LIKE ? OR 
                log_bajas.numero_serie LIKE ? OR 
                log_bajas.usuario_nombre LIKE ? OR 
                log_bajas.mac_address LIKE ? OR 
                log_bajas.registro_id LIKE ? OR
                log_bajas.tipo LIKE ? OR
                log_bajas.razon_baja LIKE ? OR
                log_bajas.observaciones LIKE ? OR
                marcas.nombre LIKE ? OR 
                usuarios_ti.usuario LIKE ? OR 
                usuarios_ti.nombres LIKE ? OR 
                usuarios_ti.apellidos LIKE ?
            ", array_fill(0, 13, "%{$sLike}%"));
        }

        // Rango de fechas para PDF
        if ($request->filled('fecha_desde')) {
            $query->whereDate('log_bajas.fecha', '>=', $request->input('fecha_desde'));
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('log_bajas.fecha', '<=', $request->input('fecha_hasta'));
        }

        $bajas = $query->orderBy('log_bajas.fecha', 'desc')->get();

        return view('admin.bajas.pdf', compact('bajas'));
    }
}