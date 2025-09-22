<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LogBajasController extends Controller
{
    /**
     * Verificar que el usuario sea ADMIN
     */
    private function verificarAdmin()
    {
        if (!Auth::check() || Auth::user()->rol !== 'ADMIN') {
            abort(403, 'Acceso no autorizado. Solo administradores pueden ver esta sección.');
        }
    }

    /**
     * Mostrar listado de todas las bajas
     */
    public function index()
    {
        $this->verificarAdmin();
        
        $bajas = DB::table('log_bajas')
            ->leftJoin('usuarios_ti', 'log_bajas.usuario_ti_id', '=', 'usuarios_ti.id')
            ->leftJoin('marcas', 'log_bajas.marca_id', '=', 'marcas.id')
            ->select(
                'log_bajas.*',
                'usuarios_ti.usuario as ti_usuario',
                'usuarios_ti.nombres as ti_nombres',
                'usuarios_ti.apellidos as ti_apellidos',
                'usuarios_ti.puesto as ti_puesto',
                'marcas.nombre as marca_nombre'
            )
            ->orderBy('log_bajas.fecha', 'desc')
            ->paginate(20);

        return view('admin.bajas.index', compact('bajas'));
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
            'log_bajas.*', // ← Trae TODOS los campos de log_bajas
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
     * Buscar bajas por criterios
     */
    public function search(Request $request)
    {
        $this->verificarAdmin();
        
        $query = DB::table('log_bajas')
            ->leftJoin('usuarios_ti', 'log_bajas.usuario_ti_id', '=', 'usuarios_ti.id')
            ->leftJoin('marcas', 'log_bajas.marca_id', '=', 'marcas.id')
            ->select(
                'log_bajas.*',
                'usuarios_ti.usuario as ti_usuario',
                'usuarios_ti.nombres as ti_nombres',
                'usuarios_ti.apellidos as ti_apellidos',
                'marcas.nombre as marca_nombre'
            );

        // Filtros de búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('log_bajas.modelo', 'LIKE', "%{$search}%")
                  ->orWhere('log_bajas.numero_serie', 'LIKE', "%{$search}%")
                  ->orWhere('log_bajas.usuario_nombre', 'LIKE', "%{$search}%")
                  ->orWhere('marcas.nombre', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('log_bajas.fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('log_bajas.fecha', '<=', $request->fecha_hasta);
        }

        $bajas = $query->orderBy('log_bajas.fecha', 'desc')->paginate(20);

        return view('admin.bajas.index', compact('bajas'));
    }

    /**
     * Exportar bajas a PDF
     */
    public function exportPdf()
    {
        $this->verificarAdmin();
        
        $bajas = DB::table('log_bajas')
            ->leftJoin('usuarios_ti', 'log_bajas.usuario_ti_id', '=', 'usuarios_ti.id')
            ->leftJoin('marcas', 'log_bajas.marca_id', '=', 'marcas.id')
            ->select(
                'log_bajas.*',
                'usuarios_ti.nombres as ti_nombres',
                'usuarios_ti.apellidos as ti_apellidos',
                'marcas.nombre as marca_nombre'
            )
            ->orderBy('log_bajas.fecha', 'desc')
            ->get();

        return view('admin.bajas.pdf', compact('bajas'));
    }
}