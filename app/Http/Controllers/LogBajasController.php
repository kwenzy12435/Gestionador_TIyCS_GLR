<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class LogBajasController extends Controller
{
    private const PAGE_SIZE = 20;

    private function verificarAdmin(): void
    {
        if (!Auth::check() || Auth::user()->rol !== 'ADMIN') {
            abort(403, 'Acceso no autorizado. Solo administradores pueden ver esta sección.');
        }
    }

    public function index(Request $request)
    {
        $this->verificarAdmin();
        return $this->search($request);
    }

    public function show($id)
    {
        $this->verificarAdmin();

    $colTipo = $this->resolverColumnaTipo();

    $select = [
        'log_bajas.*',
        'usuarios_ti.usuario as ti_usuario',
        'usuarios_ti.nombres as ti_nombres',
        'usuarios_ti.apellidos as ti_apellidos',
        'usuarios_ti.puesto as ti_puesto',
        'usuarios_ti.telefono as ti_telefono',
        'marcas.nombre as marca_nombre',
    ];

    // Alias consistente para "tipo"
    if ($colTipo) {
        $select[] = DB::raw("log_bajas.$colTipo as tipo");
    } else {
        $select[] = DB::raw("NULL as tipo");
    }

    // Email opcional (si no existe la columna, asigna NULL)
    if (\Illuminate\Support\Facades\Schema::hasColumn('usuarios_ti', 'email')) {
        $select[] = 'usuarios_ti.email as ti_email';
    } else {
        $select[] = DB::raw('NULL as ti_email');
    }

    $baja = DB::table('log_bajas')
        ->leftJoin('usuarios_ti', 'log_bajas.usuario_ti_id', '=', 'usuarios_ti.id')
        ->leftJoin('marcas', 'log_bajas.marca_id', '=', 'marcas.id')
        ->where('log_bajas.id', $id)
        ->select($select)
        ->first();

    if (!$baja) {
        abort(404, 'Registro de baja no encontrado');
    }

    return view('admin.bajas.show', compact('baja', 'colTipo'));
    }

    public function search(Request $request)
    {
        $this->verificarAdmin();

        $request->merge([
            'search'      => trim((string) $request->input('search', '')),
            'fecha_desde' => $request->input('fecha_desde') ?: null,
            'fecha_hasta' => $request->input('fecha_hasta') ?: null,
            'tipo'        => $request->input('tipo') ?: null,
        ]);

        $colTipo = $this->resolverColumnaTipo();

        $select = [
            'log_bajas.*',
            'usuarios_ti.usuario as ti_usuario',
            'usuarios_ti.nombres as ti_nombres',
            'usuarios_ti.apellidos as ti_apellidos',
            'usuarios_ti.puesto as ti_puesto',
            'marcas.nombre as marca_nombre',
        ];

        if ($colTipo) {
            $select[] = DB::raw("log_bajas.$colTipo as tipo");
        } else {
            $select[] = DB::raw("NULL as tipo");
        }

        $query = DB::table('log_bajas')
            ->leftJoin('usuarios_ti', 'log_bajas.usuario_ti_id', '=', 'usuarios_ti.id')
            ->leftJoin('marcas', 'log_bajas.marca_id', '=', 'marcas.id')
            ->select($select);

        if ($request->filled('search')) {
            $searchTerm = '%' . str_replace(['%', '_'], ['\%', '\_'], $request->input('search')) . '%';

            $query->where(function ($q) use ($searchTerm, $colTipo) {
                $q->where('log_bajas.modelo', 'LIKE', $searchTerm)
                  ->orWhere('log_bajas.numero_serie', 'LIKE', $searchTerm)
                  ->orWhere('log_bajas.usuario_nombre', 'LIKE', $searchTerm)
                  ->orWhere('log_bajas.mac_address', 'LIKE', $searchTerm)
                  ->orWhere('log_bajas.registro_id', 'LIKE', $searchTerm)
                  ->orWhere('log_bajas.razon_baja', 'LIKE', $searchTerm)
                  ->orWhere('log_bajas.observaciones', 'LIKE', $searchTerm)
                  ->orWhere('marcas.nombre', 'LIKE', $searchTerm)
                  ->orWhere('usuarios_ti.usuario', 'LIKE', $searchTerm)
                  ->orWhere('usuarios_ti.nombres', 'LIKE', $searchTerm)
                  ->orWhere('usuarios_ti.apellidos', 'LIKE', $searchTerm);

                if ($colTipo) {
                    $q->orWhere("log_bajas.$colTipo", 'LIKE', $searchTerm);
                }
            });
        }

        if ($request->filled('tipo') && $colTipo) {
            $query->where("log_bajas.$colTipo", $request->input('tipo'));
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('log_bajas.fecha', '>=', $request->input('fecha_desde'));
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('log_bajas.fecha', '<=', $request->input('fecha_hasta'));
        }

        $bajas = $query
            ->orderBy('log_bajas.fecha', 'desc')
            ->orderBy('log_bajas.created_at', 'desc')
            ->paginate(self::PAGE_SIZE)
            ->withQueryString();

        $tipos = $colTipo
            ? DB::table('log_bajas')
                ->select("$colTipo as tipo")
                ->whereNotNull($colTipo)
                ->distinct()
                ->orderBy($colTipo)
                ->get()
            : collect();

        return view('admin.bajas.index', compact('bajas', 'tipos', 'colTipo'));
    }

    public function exportPdf(Request $request)
    {
        $this->verificarAdmin();

        $colTipo = $this->resolverColumnaTipo();

        $select = [
            'log_bajas.*',
            'usuarios_ti.nombres as ti_nombres',
            'usuarios_ti.apellidos as ti_apellidos',
            'usuarios_ti.usuario as ti_usuario',
            'marcas.nombre as marca_nombre',
        ];

        if ($colTipo) {
            $select[] = DB::raw("log_bajas.$colTipo as tipo");
        } else {
            $select[] = DB::raw("NULL as tipo");
        }

        $query = DB::table('log_bajas')
            ->leftJoin('usuarios_ti', 'log_bajas.usuario_ti_id', '=', 'usuarios_ti.id')
            ->leftJoin('marcas', 'log_bajas.marca_id', '=', 'marcas.id')
            ->select($select);

        if ($request->filled('search')) {
            $searchTerm = '%' . str_replace(['%', '_'], ['\%', '\_'], $request->input('search')) . '%';

            $query->where(function ($q) use ($searchTerm, $colTipo) {
                $q->where('log_bajas.modelo', 'LIKE', $searchTerm)
                  ->orWhere('log_bajas.numero_serie', 'LIKE', $searchTerm)
                  ->orWhere('log_bajas.usuario_nombre', 'LIKE', $searchTerm)
                  ->orWhere('log_bajas.mac_address', 'LIKE', $searchTerm);

                if ($colTipo) {
                    $q->orWhere("log_bajas.$colTipo", 'LIKE', $searchTerm);
                }

                $q->orWhere('marcas.nombre', 'LIKE', $searchTerm)
                  ->orWhere('usuarios_ti.usuario', 'LIKE', $searchTerm)
                  ->orWhere('usuarios_ti.nombres', 'LIKE', $searchTerm);
            });
        }

        if ($request->filled('tipo') && $colTipo) {
            $query->where("log_bajas.$colTipo", $request->input('tipo'));
        }

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

        return view('admin.bajas.pdf', compact('bajas', 'filtrosAplicados', 'colTipo'));
    }

    private function resolverColumnaTipo(): ?string
    {
        foreach (['tipo', 'tipo_baja', 'categoria', 'motivo', 'clase'] as $c) {
            if (Schema::hasColumn('log_bajas', $c)) {
                return $c;
            }
        }
        return null;
    }
}
