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
        return ['nuevo', 'asignado', 'reparación', 'baja'];
    }
    
    public function index(Request $request)
    {
        $search = trim((string)$request->input('search', ''));
        $tipoId = $request->input('tipo_id');
        $marcaId = $request->input('marca_id');
        $estado = $request->input('estado');

        $dispositivos = InventarioDispositivo::with(['tipo', 'marca', 'colaborador'])
            ->when($search !== '', function ($query) use ($search) {
                $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $search) . '%';
                $query->where(function($q) use ($like) {
                    $q->where('modelo', 'LIKE', $like)
                      ->orWhere('numero_serie', 'LIKE', $like)
                      ->orWhere('serie', 'LIKE', $like)
                      ->orWhere('mac', 'LIKE', $like)
                      ->orWhere('procesador', 'LIKE', $like)
                      ->orWhere('memoria_ram', 'LIKE', $like)
                      ->orWhere('ssd', 'LIKE', $like)
                      ->orWhere('hdd', 'LIKE', $like)
                      ->orWhere('color', 'LIKE', $like)
                      ->orWhereHas('tipo', function($q) use ($like) {
                          $q->where('nombre', 'LIKE', $like);
                      })
                      ->orWhereHas('marca', function($q) use ($like) {
                          $q->where('nombre', 'LIKE', $like);
                      })
                      ->orWhereHas('colaborador', function($q) use ($like) {
                          $q->where('nombre', 'LIKE', $like)
                            ->orWhere('apellidos', 'LIKE', $like)
                            ->orWhere('usuario', 'LIKE', $like);
                      });
                });
            })
            ->when($tipoId, fn($q) => $q->where('tipo_id', $tipoId))
            ->when($marcaId, fn($q) => $q->where('marca_id', $marcaId))
            ->when($estado, fn($q) => $q->where('estado', $estado))
            ->orderBy('estado')
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $tipos = TipoDispositivo::orderBy('nombre')->get(['id','nombre']);
        $marcas = Marca::orderBy('nombre')->get(['id','nombre']);
        $estados = $this->estados();

        return view('inventario_dispositivos.index', compact(
            'dispositivos', 'tipos', 'marcas', 'estados', 'search', 'tipoId', 'marcaId', 'estado'
        ));
    }

    public function create()
    {
        $tipos = TipoDispositivo::orderBy('nombre')->get();
        $marcas = Marca::orderBy('nombre')->get();
        $colaboradores = Colaborador::orderBy('nombre')->get();
        $estados = $this->estados();
        
        return view('inventario_dispositivos.create', compact('tipos', 'marcas', 'colaboradores', 'estados'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'estado' => 'required|in:nuevo,asignado,reparación,baja',
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

    public function show(InventarioDispositivo $inventarioDispositivo)
    {  
        $inventarioDispositivo->load(['tipo', 'marca', 'colaborador']);
        
        $qrData = $this->generarDatosQR($inventarioDispositivo);
        $qrCodePng = $this->generarQRCode($qrData, 150, 'png');
        $qrCodeBase64 = 'data:image/png;base64,' . base64_encode($qrCodePng);

        return view('inventario_dispositivos.show', compact('inventarioDispositivo', 'qrCodeBase64'));
    }

    public function edit(InventarioDispositivo $inventarioDispositivo)
    {
        $tipos = TipoDispositivo::orderBy('nombre')->get();
        $marcas = Marca::orderBy('nombre')->get();
        $colaboradores = Colaborador::orderBy('nombre')->get();
        $estados = $this->estados();
        
        return view('inventario_dispositivos.edit', compact('inventarioDispositivo', 'tipos', 'marcas', 'colaboradores', 'estados'));
    }

    public function update(Request $request, InventarioDispositivo $inventarioDispositivo)
    {
        $validated = $request->validate([
            'estado' => 'required|in:nuevo,asignado,reparación,baja',
            'tipo_id' => 'required|exists:tipos_dispositivo,id',
            'marca_id' => 'required|exists:marcas,id',
            'mac' => [
                'nullable', 
                'string', 
                'max:50', 
                'regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/',
                Rule::unique('inventario_dispositivos')->ignore($inventarioDispositivo->id)
            ],
            'modelo' => 'required|string|max:100',
            'serie' => 'nullable|string|max:100',
            'numero_serie' => [
                'required',
                'string',
                'max:100',
                Rule::unique('inventario_dispositivos')->ignore($inventarioDispositivo->id)
            ],
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
            $inventarioDispositivo->update($validated);

            return redirect()->route('inventario-dispositivos.index')
                ->with('success', 'Dispositivo actualizado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar el dispositivo: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(InventarioDispositivo $inventarioDispositivo)
    {
        try {
            $inventarioDispositivo->load(['colaborador']);
            $usuario = auth()->user();

            DB::transaction(function () use ($inventarioDispositivo, $usuario) {
                $this->registrarBajaEnLog($inventarioDispositivo, $usuario);
                $inventarioDispositivo->delete();
            });
            
            return redirect()->route('inventario-dispositivos.index')
                ->with('success', 'Dispositivo eliminado correctamente.');
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el dispositivo: ' . $e->getMessage());
        }
    }

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

    public function generarQR(InventarioDispositivo $inventarioDispositivo)
    {
        try {
            $inventarioDispositivo->load(['tipo', 'marca', 'colaborador']);
            $texto = $this->generarDatosQR($inventarioDispositivo);
            $size = request('size', 300);
            $format = request('format', 'png');  
            $qrContent = $this->generarQRCode($texto, $size, $format);
            $contentType = $format === 'svg' ? 'image/svg+xml' : 'image/png';
            $filename = "qr_dispositivo_{$inventarioDispositivo->id}.{$format}";

            return response($qrContent, 200)
                    ->header('Content-Type', $contentType)
                    ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");

        } catch (\Exception $e) {
            abort(500, 'Error al generar QR: ' . $e->getMessage());
        }
    }

    private function generarQRCode($data, $size = 200, $format = 'png')
    {
        $writer = $format === 'svg' ? new SvgWriter() : new PngWriter();

        $result = Builder::create()
            ->writer($writer)
            ->data($data)
            ->size((int)$size)
            ->margin(10)
            ->encoding(new Encoding('UTF-8'))
            ->foregroundColor(new Color(0, 0, 0))
            ->backgroundColor(new Color(255, 255, 255))
            ->build();

        return $result->getString();
    }

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