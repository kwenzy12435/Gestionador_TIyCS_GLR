<?php

namespace App\Http\Controllers;

use App\Models\InventarioDispositivo;
use App\Models\TipoDispositivo;
use App\Models\Marca;
use App\Models\Colaborador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Carbon\Carbon;

class InventarioDispositivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      
        $dispositivos = InventarioDispositivo::with(['tipo', 'marca', 'colaborador'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('inventario_dispositivos.index', compact('dispositivos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tipos = TipoDispositivo::all();
        $marcas = Marca::all();
        $colaboradores = Colaborador::all();
        $estados = ['nuevo', 'asignado', 'baja', 'reparación'];
        
        return view('inventario_dispositivos.create', compact('tipos', 'marcas', 'colaboradores', 'estados'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'estado' => 'required|in:nuevo,asignado,baja,reparación',
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

        // Generar QR para la vista show
        $qrData = $this->generarDatosQR($dispositivo);
        $qrCodeSvg = $this->generarQRCode($qrData, 150, 'svg');

        return view('inventario_dispositivos.show', compact('dispositivo', 'qrCodeSvg'));
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
        $estados = ['nuevo', 'asignado', 'baja', 'reparación'];
        
        return view('inventario_dispositivos.edit', compact('dispositivo', 'tipos', 'marcas', 'colaboradores', 'estados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $dispositivo = InventarioDispositivo::findOrFail($id);

        $validated = $request->validate([
            'estado' => 'required|in:nuevo,asignado,baja,reparación',
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
                
                // Eliminar el dispositivo
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
     $qrCode = QrCode::create($texto)
        ->setSize(300)
        ->setMargin(10)
        ->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH);

    $writer = new PngWriter();
    $result = $writer->write($qrCode);

    return response($result->getString(), 200)
            ->header('Content-Type', $result->getMimeType());
    }

    /**
     * Generar página de QR imprimible
     */
    public function qrImprimible($id)
    {
        $dispositivo = InventarioDispositivo::with(['tipo', 'marca', 'colaborador'])
            ->findOrFail($id);

        $qrData = $this->generarDatosQR($dispositivo);
        $qrCodeSvg = $this->generarQRCode($qrData, 200, 'svg');

        return view('inventario_dispositivos.qr_imprimible', compact('dispositivo', 'qrCodeSvg', 'qrData'));
    }

    /**
     * Función auxiliar para generar QR Code con Endroid v4+
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
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->foregroundColor(new Color(0, 0, 0))
            ->backgroundColor(new Color(255, 255, 255))
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->build();

        return $result->getString();
    }

    /**
     * Función auxiliar para generar datos del QR
     */
    private function generarDatosQR($dispositivo)
    {
        $datos = "INVENTARIO DE DISPOSITIVO\n";
        $datos .= "=======================\n";
        $datos .= "ID: {$dispositivo->id}\n";
        $datos .= "Tipo: {$dispositivo->tipo->nombre}\n";
        $datos .= "Marca: {$dispositivo->marca->nombre}\n";
        $datos .= "Modelo: {$dispositivo->modelo}\n";
        $datos .= "N° Serie: {$dispositivo->numero_serie}\n";
        
        if ($dispositivo->serie && $dispositivo->serie != 'N/A') {
            $datos .= "Serie: {$dispositivo->serie}\n";
        }
        
        $datos .= "Estado: " . ucfirst($dispositivo->estado) . "\n";
        
        if ($dispositivo->mac && $dispositivo->mac != 'N/A') {
            $datos .= "MAC: {$dispositivo->mac}\n";
        }
        
        if ($dispositivo->colaborador) {
            $datos .= "Asignado a: {$dispositivo->colaborador->nombre} {$dispositivo->colaborador->apellidos}\n";
        } else {
            $datos .= "Asignado a: No asignado\n";
        }
        
        // Información técnica adicional si existe
        if ($dispositivo->procesador && $dispositivo->procesador != 'N/A') {
            $datos .= "Procesador: {$dispositivo->procesador}\n";
        }
        
        if ($dispositivo->memoria_ram && $dispositivo->memoria_ram != 'N/A') {
            $datos .= "RAM: {$dispositivo->memoria_ram}\n";
        }
        
        $datos .= "Actualizado: " . Carbon::parse($dispositivo->updated_at)->format('d/m/Y');

        return $datos;
    }
}