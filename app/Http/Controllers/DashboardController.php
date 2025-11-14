<?php

namespace App\Http\Controllers;

use App\Models\InventarioDispositivo;
use App\Models\Licencia;
use App\Models\ReporteActividad;
use App\Models\Colaborador;
use App\Models\UsuarioTI;
use App\Models\Articulo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            $cacheTtl = 120; // segundos

            [$desde, $hasta] = $this->rangoUltimosDias(6);

            $totales = Cache::remember('dash.totales', $cacheTtl, function () {
                return [
                    'totalDispositivos'  => InventarioDispositivo::count(),
                    'dispositivosActivos'=> InventarioDispositivo::where('estado', 'Activo')->count(),
                    'totalColaboradores' => Colaborador::count(),
                    'totalUsuariosTI'    => UsuarioTI::count(),
                    'totalArticulos'     => Articulo::count(),
                ];
            });

            $licenciasPorEstado = Cache::remember('dash.licencias', $cacheTtl, function () {
                // Categorías mutuamente excluyentes
                return Licencia::selectRaw("
                    SUM(CASE WHEN fecha_expiracion < NOW() THEN 1 ELSE 0 END)                     AS expiradas,
                    SUM(CASE WHEN fecha_expiracion >= NOW() AND fecha_expiracion <= DATE_ADD(NOW(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) AS por_expiar,
                    SUM(CASE WHEN fecha_expiracion > DATE_ADD(NOW(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) AS activas
                ")->first()->toArray();
            });

            $licenciasPorExpiar = (int)($licenciasPorEstado['por_expiar'] ?? 0);

            $reportesRecientes = Cache::remember("dash.reportes7d", $cacheTtl, function () {
                return ReporteActividad::where('created_at', '>=', now()->subDays(7))->count();
            });

            $dispositivosRecientes = Cache::remember('dash.dispRecientes', $cacheTtl, function () {
                return InventarioDispositivo::select('id','modelo','tipo','estado','colaborador_id','created_at')
                    ->with(['colaborador:id,nombre,apellidos'])
                    ->latest('created_at')
                    ->limit(5)
                    ->get();
            });

            $actividadReciente = Cache::remember('dash.actReciente', $cacheTtl, function () {
                return ReporteActividad::select('id','actividad','descripcion','created_at','colaborador_id','usuario_ti_id','naturaleza_id')
                    ->with([
                        'colaborador:id,nombre,apellidos',
                        'usuarioTi:id,usuario',
                        'naturaleza:id,nombre'
                    ])
                    ->latest('created_at')
                    ->limit(5)
                    ->get();
            });

            $dispositivosPorEstado = Cache::remember('dash.dispEstado', $cacheTtl, function () {
                return InventarioDispositivo::select('estado', DB::raw('COUNT(*) as total'))
                    ->groupBy('estado')
                    ->pluck('total','estado')
                    ->toArray();
            });

            $actividadPorDia = Cache::remember("dash.actDia.$desde.$hasta", $cacheTtl, function () use ($desde, $hasta) {
                $rows = ReporteActividad::selectRaw("DATE(created_at) as d, COUNT(*) as c")
                    ->whereBetween(DB::raw('DATE(created_at)'), [$desde, $hasta])
                    ->groupBy('d')
                    ->pluck('c','d')
                    ->toArray();

                // Rellenar días faltantes con 0
                $serie = [];
                $period = new \DatePeriod(
                    Carbon::parse($desde),
                    new \DateInterval('P1D'),
                    Carbon::parse($hasta)->addDay()
                );
                foreach ($period as $day) {
                    $key = $day->format('Y-m-d');
                    $serie[$key] = (int)($rows[$key] ?? 0);
                }
                return $serie;
            });

            return view('dashboard', [
                'totalDispositivos'   => $totales['totalDispositivos'],
                'dispositivosActivos' => $totales['dispositivosActivos'],
                'licenciasPorExpiar'  => $licenciasPorExpiar,
                'reportesRecientes'   => $reportesRecientes,
                'totalColaboradores'  => $totales['totalColaboradores'],
                'totalUsuariosTI'     => $totales['totalUsuariosTI'],
                'totalArticulos'      => $totales['totalArticulos'],
                'dispositivosRecientes'=> $dispositivosRecientes,
                'actividadReciente'   => $actividadReciente,
                'dispositivosPorEstado'=> $dispositivosPorEstado,
                'actividadPorDia'     => $actividadPorDia,
                'licenciasPorEstado'  => $licenciasPorEstado,
            ]);

        } catch (\Throwable $e) {
            return $this->showDemoDashboard();
        }
    }

    private function rangoUltimosDias(int $dias = 6): array
    {
        $hasta = now()->format('Y-m-d');
        $desde = now()->subDays($dias)->format('Y-m-d');
        return [$desde, $hasta];
    }

    private function showDemoDashboard()
    {
        $dispositivosRecientes = collect([
            (object)['id'=>1, 'modelo'=>'Laptop Dell Vostro', 'colaborador'=>(object)['nombre'=>'Juan', 'apellidos'=>'García'], 'estado'=>'Activo', 'tipo'=>'Laptop'],
            (object)['id'=>2, 'modelo'=>'iPhone XR', 'colaborador'=>(object)['nombre'=>'María', 'apellidos'=>'López'], 'estado'=>'En revisión', 'tipo'=>'Móvil'],
            (object)['id'=>3, 'modelo'=>'AP Ubiquiti', 'colaborador'=>null, 'estado'=>'Inactivo', 'tipo'=>'Red'],
        ]);

        return view('dashboard', [
            'totalDispositivos'    => 3,
            'dispositivosActivos'  => 1,
            'licenciasPorExpiar'   => 2,
            'reportesRecientes'    => 5,
            'totalColaboradores'   => 15,
            'totalUsuariosTI'      => 3,
            'totalArticulos'       => 8,
            'dispositivosRecientes'=> $dispositivosRecientes,
            'actividadReciente'    => collect(),
            'dispositivosPorEstado'=> ['Activo' => 1, 'En revisión' => 1, 'Inactivo' => 1],
            'actividadPorDia'      => [
                now()->subDays(6)->format('Y-m-d') => 0,
                now()->subDays(5)->format('Y-m-d') => 0,
                now()->subDays(4)->format('Y-m-d') => 0,
                now()->subDays(3)->format('Y-m-d') => 0,
                now()->subDays(2)->format('Y-m-d') => 0,
                now()->subDays(1)->format('Y-m-d') => 0,
                now()->format('Y-m-d')            => 0,
            ],
            'licenciasPorEstado'   => ['activas' => 5, 'por_expiar' => 2, 'expiradas' => 1],
        ]);
    }
}
