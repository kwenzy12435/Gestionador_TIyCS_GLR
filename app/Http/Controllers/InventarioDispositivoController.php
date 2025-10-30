<?php

namespace App\Http\Controllers;

use App\Models\InventarioDispositivo;
use App\Models\TipoDispositivo;
use App\Models\Marca;
use App\Models\Colaborador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Color\Color;
use Carbon\Carbon;

class InventarioDispositivoController extends Controller
{
    private function estados(): array
    {
        return ['nuevo', 'asignado', 'reparación'];
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search      = trim((string)$request->input('search', ''));
        $tipoId      = $request->input('tipo_id');
        $marcaId     = $request->input('marca_id');
        $estado      = $request->input('estado');

        $dispositivos = InventarioDispositivo::with(['tipo', 'marca', 'colaborador'])
            ->when($search !== '', function ($q) use ($search) {
                $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $search) . '%';
                // Reemplazar múltiples OR con SQL directo para mejor rendimiento
                $q->whereRaw("
                    modelo LIKE ? OR 
                    numero_serie LIKE ? OR 
                    serie LIKE ? OR 
                    mac LIKE ? OR 
                    procesador LIKE ? OR 
                    memoria_ram LIKE ? OR 
                    ssd LIKE ? OR 
                    hdd LIKE ? OR 
                    color LIKE ? OR
                    tipo_id IN (SELECT id FROM tipos_dispositivo WHERE nombre LIKE ?) OR
                    marca_id IN (SELECT id FROM marcas WHERE nombre LIKE ?) OR
                    colaborador_id IN (SELECT id FROM colaboradores WHERE nombre LIKE ? OR apellidos LIKE ? OR email LIKE ?)
                ", array_fill(0, 14, $like));
            })
            ->when($tipoId,  fn($q) => $q->where('tipo_id',  $tipoId))
            ->when($marcaId, fn($q) => $q->where('marca_id', $marcaId))
            ->when($estado,  fn($q) => $q->where('estado',   $estado))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->appends($request->query());

        $tipos        = TipoDispositivo::orderBy('nombre')->get(['id','nombre']);
        $marcas       = Marca::orderBy('nombre')->get(['id','nombre']);
        $estados      = $this->estados();

        return view('inventario_dispositivos.index', compact('dispositivos','tipos','marcas','estados','search','tipoId','marcaId','estado'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tipos = TipoDispositivo::all();
        $marcas = Marca::all();
        $colaboradores = Colaborador::all();
        $estados = ['nuevo', 'asignado', 'reparación'];
        
        return view('inventario_dispositivos.create', compact('tipos', 'marcas', 'colaboradores', 'estados'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'estado' => 'required|in:nuevo,asignado,reparación',
            'tipo_id' => 'required|exists:tipos_dispositivo,id',
            'marca_id' => 'required|exists:marcas,id',
            'mac' => 'nullable|string|max:50|unique:inventario_dispositivos|regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/',
            'modelo' => 'required|string|max:100',
            'serie' => 'nullable|string|max:100',
            'numero_serie' => 'required|string|max:100|unique:inventario_dispositivos',
            'procesador' => 'nullable|string|max:100',
            'memoria_ram' => 'nullable|string|max:100',
            'ssd' => 'nullable|string|max:100',
            'hdd' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
            'costo' => 'nullable|numeric|min:0|max:999999.99',
            'fecha_compra' => 'nullable|date|before_or_equal:today',
            'garantia_hasta' => 'nullable|date|after:fecha_compra',
            'colaborador_id' => 'nullable|exists:colaboradores,id'
        ], [
            'mac.regex' => 'El formato MAC debe ser: XX:XX:XX:XX:XX:XX o XX-XX-XX-XX-XX-XX',
            'mac.unique' => 'Esta dirección MAC ya está registrada en el sistema',
            'numero_serie.unique' => 'Este número de serie ya está registrado en el sistema',
            'fecha_compra.before_or_equal' => 'La fecha de compra no puede ser futura',
            'garantia_hasta.after' => 'La fecha de garantía debe ser posterior a la fecha de compra',
            'costo.max' => 'El costo no puede ser mayor a 999,999.99'
        ]);

        try {
            InventarioDispositivo::create($validated);

            return redirect()->route('inventario-dispositivos.index')
                ->with('success', 'Dispositivo creado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear el dispositivo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {  
        $dispositivo = InventarioDispositivo::with(['tipo', 'marca', 'colaborador'])
            ->findOrFail($id);

        
        $qrData = $this->generarDatosQR($dispositivo);
        $qrCodePng = $this->generarQRCode($qrData, 150, 'png');
        $qrCodeBase64 = 'data:image/png;base64,' . base64_encode($qrCodePng);

        return view('inventario_dispositivos.show', compact('dispositivo', 'qrCodeBase64'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $dispositivo = InventarioDispositivo::findOrFail($id);

        $tipos = TipoDispositivo::all();
        $marcas = Marca::all();
        $colaboradores = Colaborador::all();
        $estados = ['nuevo', 'asignado', 'reparación'];
        
        return view('inventario_dispositivos.edit', compact('dispositivo', 'tipos', 'marcas', 'colaboradores', 'estados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $dispositivo = InventarioDispositivo::findOrFail($id);

        $validated = $request->validate([
            'estado' => 'required|in:nuevo,asignado,reparación',
            'tipo_id' => 'required|exists:tipos_dispositivo,id',
            'marca_id' => 'required|exists:marcas,id',
            'mac' => 'nullable|string|max:50|unique:inventario_dispositivos,mac,' . $id . '|regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/',
            'modelo' => 'required|string|max:100',
            'serie' => 'nullable|string|max:100',
            'numero_serie' => 'required|string|max:100|unique:inventario_dispositivos,numero_serie,' . $id,
            'procesador' => 'nullable|string|max:100',
            'memoria_ram' => 'nullable|string|max:100',
            'ssd' => 'nullable|string|max:100',
            'hdd' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
            'costo' => 'nullable|numeric|min:0|max:999999.99',
            'fecha_compra' => 'nullable|date|before_or_equal:today',
            'garantia_hasta' => 'nullable|date|after:fecha_compra',
            'colaborador_id' => 'nullable|exists:colaboradores,id'
        ], [
            'mac.regex' => 'El formato MAC debe ser: XX:XX:XX:XX:XX:XX o XX-XX-XX-XX-XX-XX',
            'mac.unique' => 'Esta dirección MAC ya está registrada en el sistema',
            'numero_serie.unique' => 'Este número de serie ya está registrado en el sistema',
            'fecha_compra.before_or_equal' => 'La fecha de compra no puede ser futura',
            'garantia_hasta.after' => 'La fecha de garantía debe ser posterior a la fecha de compra'
        ]);

        try {
            $dispositivo->update($validated);

            return redirect()->route('inventario-dispositivos.index')
                ->with('success', 'Dispositivo actualizado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar el dispositivo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $dispositivo = InventarioDispositivo::with(['colaborador'])->findOrFail($id);
            $usuario = auth()->user();

            DB::transaction(function () use ($dispositivo, $usuario) {
                $this->registrarBajaEnLog($dispositivo, $usuario);
                $dispositivo->delete();
            });
            
            return redirect()->back()->with('success', 'Dispositivo eliminado correctamente.');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar el dispositivo: ' . $e->getMessage());
        }
    }

    /**
     * Registrar baja en log (método auxiliar)
     */
    private function registrarBajaEnLog($dispositivo, $usuario)
    {
        $nombreCompleto = $usuario->nombres . ' ' . ($usuario->apellidos ?? '');
        $colaboradorNombre = $dispositivo->colaborador ? 
            $dispositivo->colaborador->nombre . ' ' . $dispositivo->colaborador->apellidos : null;

        DB::table('log_bajas')->insert([
            'tabla_afectada' => 'inventario_dispositivos',
            'registro_id' => $dispositivo->id,
            'usuario_ti_id' => $usuario->id,
            'usuario_nombre_completo' => $nombreCompleto,
            'accion' => 'DELETE',
            'estado_texto' => $dispositivo->estado,
            'usuario_nombre' => $colaboradorNombre,
            'marca_id' => $dispositivo->marca_id,
            'numero_serie' => $dispositivo->numero_serie ?? $dispositivo->serie,
            'modelo' => $dispositivo->modelo,
            'mac_address' => $dispositivo->mac,
            'fecha_ultima_edicion' => $dispositivo->updated_at,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Generar QR para descargar
     */
    public function generarQR($id)
    {
        try {
            $dispositivo = InventarioDispositivo::with(['tipo', 'marca', 'colaborador'])->findOrFail($id);
            $texto = $this->generarDatosQR($dispositivo);
            $size = request('size', 300);
            $format = request('format', 'png');  
            $qrContent = $this->generarQRCode($texto, $size, $format);
            $contentType = $format === 'svg' ? 'image/svg+xml' : 'image/png';
            $filename = "qr_dispositivo_{$id}.{$format}";

            return response($qrContent, 200)
                    ->header('Content-Type', $contentType)
                    ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Dispositivo no encontrado');
        } catch (\Exception $e) {
            abort(500, 'Error al generar QR: ' . $e->getMessage());
        }
    }

    /**
     * Función auxiliar para generar QR Code con Endroid v4.0.0
     */
    private function generarQRCode($data, $size = 200, $format = 'png')
    {
        $writer = $format === 'svg' ? new SvgWriter() : new PngWriter();

        $result = Builder::create()
            ->writer($writer)
            ->data($data)
            ->size((int)$size)
            ->margin(10)
            ->encoding(new Encoding('UTF-8'))
            ->foregroundColor(new \Endroid\QrCode\Color\Color(0, 0, 0))
            ->backgroundColor(new \Endroid\QrCode\Color\Color(255, 255, 255))
            ->build();

        return $result->getString();
    }

    /**
     * Función auxiliar para generar datos del QR
     */
    private function generarDatosQR($dispositivo)
    {
        if (!$dispositivo) {
            return "Dispositivo no encontrado";
        }

        $datos = "INVENTARIO DE DISPOSITIVO\n";
        $datos .= "=======================\n";
        $datos .= "ID: " . ($dispositivo->id ?? 'N/A') . "\n";
        $datos .= "Tipo: " . ($dispositivo->tipo->nombre ?? 'No especificado') . "\n";
        $datos .= "Marca: " . ($dispositivo->marca->nombre ?? 'No especificado') . "\n";
        $datos .= "Modelo: " . ($dispositivo->modelo ?? 'N/A') . "\n";
        $datos .= "N° Serie: " . ($dispositivo->numero_serie ?? 'N/A') . "\n";
        
        if (isset($dispositivo->serie) && $dispositivo->serie != 'N/A') {
            $datos .= "Serie: " . $dispositivo->serie . "\n";
        }
        
        $datos .= "Estado: " . ucfirst($dispositivo->estado ?? 'desconocido') . "\n";
        
        if (isset($dispositivo->mac) && $dispositivo->mac != 'N/A') {
            $datos .= "MAC: " . $dispositivo->mac . "\n";
        }
        
        if ($dispositivo->colaborador) {
            $datos .= "Asignado a: " . ($dispositivo->colaborador->nombre ?? '') . " " . ($dispositivo->colaborador->apellidos ?? '') . "\n";
        } else {
            $datos .= "Asignado a: No asignado\n";
        }
        
        if ($dispositivo->procesador && $dispositivo->procesador != 'N/A') {
            $datos .= "Procesador: " . $dispositivo->procesador . "\n";
        }
        
        if ($dispositivo->memoria_ram && $dispositivo->memoria_ram != 'N/A') {
            $datos .= "RAM: " . $dispositivo->memoria_ram . "\n";
        }
        
        $datos .= "Actualizado: " . Carbon::parse($dispositivo->updated_at)->format('d/m/Y');

        return $datos;
    }
}