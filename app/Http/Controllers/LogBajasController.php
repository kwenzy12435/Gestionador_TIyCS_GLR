<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
                'usuarios_ti.email as ti_email',
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
            'tipo'         => $request->input('tipo') ?: null,
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
            $searchTerm = '%' . str_replace(['%', '_'], ['\%', '\_'], $request->input('search')) . '%';
            
            $query->where(function($q) use ($searchTerm) {
                $q->where('log_bajas.modelo', 'LIKE', $searchTerm)
                  ->orWhere('log_bajas.numero_serie', 'LIKE', $searchTerm)
                  ->orWhere('log_bajas.usuario_nombre', 'LIKE', $searchTerm)
                  ->orWhere('log_bajas.mac_address', 'LIKE', $searchTerm)
                  ->orWhere('log_bajas.registro_id', 'LIKE', $searchTerm)
                  ->orWhere('log_bajas.tipo', 'LIKE', $searchTerm)
                  ->orWhere('log_bajas.razon_baja', 'LIKE', $searchTerm)
                  ->orWhere('log_bajas.observaciones', 'LIKE', $searchTerm)
                  ->orWhere('marcas.nombre', 'LIKE', $searchTerm)
                  ->orWhere('usuarios_ti.usuario', 'LIKE', $searchTerm)
                  ->orWhere('usuarios_ti.nombres', 'LIKE', $searchTerm)
                  ->orWhere('usuarios_ti.apellidos', 'LIKE', $searchTerm);
            });
        }

        // Filtro por tipo
        if ($request->filled('tipo')) {
            $query->where('log_bajas.tipo', $request->input('tipo'));
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
            ->orderBy('log_bajas.created_at', 'desc')
            ->paginate(self::PAGE_SIZE)
            ->withQueryString();

        // Obtener tipos únicos para el filtro
        $tipos = DB::table('log_bajas')
            ->distinct()
            ->pluck('tipo')
            ->filter()
            ->values();

        return view('admin.bajas.index', compact('bajas', 'tipos'));
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
                'usuarios_ti.usuario as ti_usuario',
                'marcas.nombre as marca_nombre'
            );

        // Aplicar mismos filtros de búsqueda al exportar PDF
        if ($request->filled('search')) {
            $searchTerm = '%' . str_replace(['%', '_'], ['\%', '\_'], $request->input('search')) . '%';
            
            $query->where(function($q) use ($searchTerm) {
                $q->where('log_bajas.modelo', 'LIKE', $searchTerm)
                  ->orWhere('log_bajas.numero_serie', 'LIKE', $searchTerm)
                  ->orWhere('log_bajas.usuario_nombre', 'LIKE', $searchTerm)
                  ->orWhere('log_bajas.mac_address', 'LIKE', $searchTerm)
                  ->orWhere('log_bajas.tipo', 'LIKE', $searchTerm)
                  ->orWhere('marcas.nombre', 'LIKE', $searchTerm)
                  ->orWhere('usuarios_ti.usuario', 'LIKE', $searchTerm)
                  ->orWhere('usuarios_ti.nombres', 'LIKE', $searchTerm);
            });
        }

        // Filtro por tipo para PDF
        if ($request->filled('tipo')) {
            $query->where('log_bajas.tipo', $request->input('tipo'));
        }

        // Rango de fechas para PDF
        if ($request->filled('fecha_desde')) {
            $query->whereDate('log_bajas.fecha', '>=', $request->input('fecha_desde'));
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('log_bajas.fecha', '<=', $request->input('fecha_hasta'));
        }

        $bajas = $query
            ->orderBy('log_bajas.fecha', 'desc')
            ->orderBy('log_bajas.created_at', 'desc')
            ->get();

        $filtrosAplicados = [
            'search' => $request->input('search'),
            'tipo' => $request->input('tipo'),
            'fecha_desde' => $request->input('fecha_desde'),
            'fecha_hasta' => $request->input('fecha_hasta'),
        ];

        return view('admin.bajas.pdf', compact('bajas', 'filtrosAplicados'));
    }
}