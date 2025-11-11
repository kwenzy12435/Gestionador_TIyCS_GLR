<?php

namespace App\Http\Controllers;

use App\Models\InventarioDispositivo;
use App\Models\Licencia;
use App\Models\ReporteActividad;
use App\Models\Colaborador;
use App\Models\UsuarioTI;
use App\Models\BitacoraRespaldo;
use App\Models\Articulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // KPIs Principales
            $totalDispositivos = InventarioDispositivo::count();
            $dispositivosActivos = InventarioDispositivo::where('estado', 'Activo')->count();
            
            // Licencias por expirar (próximos 30 días)
            $licenciasPorExpiar = Licencia::where('fecha_expiracion', '<=', now()->addDays(30))
                ->where('fecha_expiracion', '>=', now())
                ->count();

            // Reportes de los últimos 7 días
            $reportesRecientes = ReporteActividad::whereDate('created_at', '>=', now()->subDays(7))->count();
            
            $totalColaboradores = Colaborador::count();
            $totalUsuariosTI = UsuarioTI::count();
            $totalArticulos = Articulo::count();

            // Dispositivos recientes (últimos 5)
            $dispositivosRecientes = InventarioDispositivo::with(['colaborador'])
                ->latest()
                ->limit(5)
                ->get();

            // Actividad reciente
            $actividadReciente = ReporteActividad::with(['colaborador', 'usuarioTi', 'naturaleza'])
                ->latest()
                ->limit(5)
                ->get();

            // Estadísticas para gráficas
            $dispositivosPorEstado = $this->getDispositivosPorEstado();
            $actividadPorDia = $this->getActividadUltimaSemana();
            $licenciasPorEstado = $this->getLicenciasPorEstado();

            return view('dashboard', compact(
                'totalDispositivos',
                'dispositivosActivos',
                'licenciasPorExpiar',
                'reportesRecientes',
                'totalColaboradores',
                'totalUsuariosTI',
                'totalArticulos',
                'dispositivosRecientes',
                'actividadReciente',
                'dispositivosPorEstado',
                'actividadPorDia',
                'licenciasPorEstado'
            ));

        } catch (\Exception $e) {
            // En caso de error, mostrar datos de demo
            return $this->showDemoDashboard();
        }
    }

    private function getDispositivosPorEstado()
    {
        return InventarioDispositivo::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();
    }

    private function getActividadUltimaSemana()
    {
        $actividad = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = ReporteActividad::whereDate('created_at', $date)->count();
            $actividad[$date] = $count;
        }
        return $actividad;
    }

    private function getLicenciasPorEstado()
    {
        $hoy = now();
        
        return [
            'activas' => Licencia::where('fecha_expiracion', '>', $hoy)->count(),
            'por_expiar' => Licencia::where('fecha_expiracion', '<=', $hoy->addDays(30))
                ->where('fecha_expiracion', '>=', $hoy)
                ->count(),
            'expiradas' => Licencia::where('fecha_expiracion', '<', $hoy)->count(),
        ];
    }

    private function showDemoDashboard()
    {
        // Datos de demo para cuando hay error en la base de datos
        $dispositivosRecientes = collect([
            (object)['id'=>1, 'modelo'=>'Laptop Dell Vostro', 'colaborador'=>(object)['nombres'=>'Juan', 'apellidos'=>'Garcia'], 'estado'=>'Activo', 'tipo'=>'Laptop'],
            (object)['id'=>2, 'modelo'=>'iPhone XR', 'colaborador'=>(object)['nombres'=>'Maria', 'apellidos'=>'Lopez'], 'estado'=>'En revisión', 'tipo'=>'Móvil'],
            (object)['id'=>3, 'modelo'=>'AP Ubiquiti', 'colaborador'=>null, 'estado'=>'Inactivo', 'tipo'=>'Red'],
        ]);

        return view('dashboard', [
            'totalDispositivos' => 3,
            'dispositivosActivos' => 1,
            'licenciasPorExpiar' => 2,
            'reportesRecientes' => 5,
            'totalColaboradores' => 15,
            'totalUsuariosTI' => 3,
            'totalArticulos' => 8,
            'dispositivosRecientes' => $dispositivosRecientes,
            'actividadReciente' => collect(),
            'dispositivosPorEstado' => ['Activo' => 1, 'En revisión' => 1, 'Inactivo' => 1],
            'actividadPorDia' => array_fill_keys([
                now()->subDays(6)->format('Y-m-d'),
                now()->subDays(5)->format('Y-m-d'),
                now()->subDays(4)->format('Y-m-d'),
                now()->subDays(3)->format('Y-m-d'),
                now()->subDays(2)->format('Y-m-d'),
                now()->subDays(1)->format('Y-m-d'),
                now()->format('Y-m-d')
            ], 0),
            'licenciasPorEstado' => ['activas' => 5, 'por_expiar' => 2, 'expiradas' => 1]
        ]);
    }
}