<?php

namespace App\Http\Controllers;

use App\Models\InventarioDispositivo;
use App\Models\TipoDispositivo;
use App\Models\Marca;
use App\Models\Colaborador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule; // ✅ IMPORTANTE
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Color\Color;
use Carbon\Carbon;

class InventarioDispositivoController extends Controller
{
    /* ===================== helpers de validación ===================== */
    private function estados(): array
    {
        return ['nuevo', 'asignado', 'reparación', 'baja'];
    }

    private function rules(?int $ignoreId = null): array
    {
        return [
            'estado'   => ['required', Rule::in($this->estados())],
            'tipo_id'  => ['required', 'exists:tipos_dispositivo,id'],
            'marca_id' => ['required', 'exists:marcas,id'],

            'mac' => [
                'nullable','string','max:50',
                'regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/',
                Rule::unique('inventario_dispositivos','mac')->ignore($ignoreId),
            ],
            'modelo'        => ['required','string','max:100'],
            'serie'         => ['nullable','string','max:100'],
            'numero_serie'  => ['required','string','max:100', Rule::unique('inventario_dispositivos','numero_serie')->ignore($ignoreId)],
            'procesador'    => ['nullable','string','max:100'],
            'memoria_ram'   => ['nullable','string','max:100'],
            'ssd'           => ['nullable','string','max:100'],
            'hdd'           => ['nullable','string','max:100'],
            'color'         => ['nullable','string','max:50'],
            'costo'         => ['nullable','numeric','min:0','max:999999.99'],
            'fecha_compra'  => ['nullable','date','before_or_equal:today'],
            'garantia_hasta'=> ['nullable','date','after:fecha_compra'],
            'colaborador_id'=> ['nullable','exists:colaboradores,id'],
        ];
    }

    private function messages(): array
    {
        return [
            'required'               => 'El campo :attribute es obligatorio.',
            'in'                     => 'El :attribute no es válido.',
            'exists'                 => 'El :attribute seleccionado no existe.',
            'string'                 => 'El campo :attribute debe ser texto.',
            'max'                    => 'El campo :attribute no debe exceder :max caracteres.',
            'numeric'                => 'El campo :attribute debe ser numérico.',
            'min'                    => 'El campo :attribute debe ser al menos :min.',
            'date'                   => 'El campo :attribute debe ser una fecha válida.',
            'before_or_equal'        => 'La :attribute no puede ser futura.',
            'after'                  => 'La :attribute debe ser posterior a la fecha de compra.',
            'unique'                 => 'Este :attribute ya está registrado.',
            'mac.regex'              => 'La MAC debe tener el formato XX:XX:XX:XX:XX:XX o XX-XX-XX-XX-XX-XX.',
        ];
    }

    private function attributes(): array
    {
        return [
            'estado'         => 'estado',
            'tipo_id'        => 'tipo',
            'marca_id'       => 'marca',
            'mac'            => 'dirección MAC',
            'modelo'         => 'modelo',
            'serie'          => 'serie alterna',
            'numero_serie'   => 'número de serie',
            'procesador'     => 'procesador',
            'memoria_ram'    => 'memoria RAM',
            'ssd'            => 'SSD',
            'hdd'            => 'HDD',
            'color'          => 'color',
            'costo'          => 'costo',
            'fecha_compra'   => 'fecha de compra',
            'garantia_hasta' => 'fecha de garantía',
            'colaborador_id' => 'colaborador asignado',
        ];
    }

    /* ============================ CRUD ============================ */

    public function index(Request $request)
    {
        $search  = trim((string) $request->input('search',''));
        $tipoId  = $request->input('tipo_id');
        $marcaId = $request->input('marca_id');
        $estado  = $request->input('estado');

        $dispositivos = InventarioDispositivo::with(['tipo','marca','colaborador'])
            ->when($search !== '', function ($query) use ($search) {
                $like = '%' . str_replace(['%','_'], ['\%','\_'], $search) . '%';
                $query->where(function($q) use ($like) {
                    $q->where('modelo','LIKE',$like)
                      ->orWhere('numero_serie','LIKE',$like)
                      ->orWhere('serie','LIKE',$like)
                      ->orWhere('mac','LIKE',$like)
                      ->orWhere('procesador','LIKE',$like)
                      ->orWhere('memoria_ram','LIKE',$like)
                      ->orWhere('ssd','LIKE',$like)
                      ->orWhere('hdd','LIKE',$like)
                      ->orWhere('color','LIKE',$like)
                      ->orWhereHas('tipo', fn($qq)=>$qq->where('nombre','LIKE',$like))
                      ->orWhereHas('marca', fn($qq)=>$qq->where('nombre','LIKE',$like))
                      ->orWhereHas('colaborador', function($qq) use ($like){
                          $qq->where('nombre','LIKE',$like)
                             ->orWhere('apellidos','LIKE',$like)
                             ->orWhere('usuario','LIKE',$like);
                      });
                });
            })
            ->when($tipoId,  fn($q)=>$q->where('tipo_id', $tipoId))
            ->when($marcaId, fn($q)=>$q->where('marca_id',$marcaId))
            ->when($estado,  fn($q)=>$q->where('estado', $estado))
            ->orderBy('estado')->orderByDesc('created_at')
            ->paginate(20)->withQueryString();

        $tipos   = TipoDispositivo::orderBy('nombre')->get(['id','nombre']);
        $marcas  = Marca::orderBy('nombre')->get(['id','nombre']);
        $estados = $this->estados();

        return view('inventario_dispositivos.index', compact(
            'dispositivos','tipos','marcas','estados','search','tipoId','marcaId','estado'
        ));
    }

    public function create()
    {
        $tipos = TipoDispositivo::orderBy('nombre')->get();
        $marcas = Marca::orderBy('nombre')->get();
        $colaboradores = Colaborador::orderBy('nombre')->get();
        $estados = $this->estados();

        return view('inventario_dispositivos.create', compact('tipos','marcas','colaboradores','estados'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages(), $this->attributes());

        try {
            InventarioDispositivo::create($validated);
            return redirect()->route('inventario-dispositivos.index')
                ->with('success','Dispositivo creado exitosamente.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error','Error al crear el dispositivo: '.$e->getMessage());
        }
    }

    public function show(InventarioDispositivo $inventarioDispositivo)
    {
        $inventarioDispositivo->load(['tipo','marca','colaborador']);

        $qrData = $this->generarDatosQR($inventarioDispositivo);
        // ✅ Fallback a SVG si GD no está disponible
        $format = function_exists('imagecreatetruecolor') ? 'png' : 'svg';
        $qrBin  = $this->generarQRCode($qrData, 150, $format);
        $qrCodeBase64 = 'data:image/'.($format==='svg'?'svg+xml':'png').';base64,'.base64_encode($qrBin);

        return view('inventario_dispositivos.show', compact('inventarioDispositivo','qrCodeBase64'));
    }

    public function edit(InventarioDispositivo $inventarioDispositivo)
    {
        $tipos = TipoDispositivo::orderBy('nombre')->get();
        $marcas = Marca::orderBy('nombre')->get();
        $colaboradores = Colaborador::orderBy('nombre')->get();
        $estados = $this->estados();

        return view('inventario_dispositivos.edit', compact(
            'inventarioDispositivo','tipos','marcas','colaboradores','estados'
        ));
    }

    public function update(Request $request, InventarioDispositivo $inventarioDispositivo)
    {
        $validated = $request->validate(
            $this->rules($inventarioDispositivo->id),
            $this->messages(),
            $this->attributes()
        );

        try {
            $inventarioDispositivo->update($validated);
            return redirect()->route('inventario-dispositivos.index')
                ->with('success','Dispositivo actualizado exitosamente.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error','Error al actualizar el dispositivo: '.$e->getMessage());
        }
    }

    public function destroy(InventarioDispositivo $inventarioDispositivo)
    {
        try {
            $inventarioDispositivo->load('colaborador');
            $usuario = auth()->user();

            DB::transaction(function () use ($inventarioDispositivo, $usuario) {
                $this->registrarBajaEnLog($inventarioDispositivo, $usuario);
                $inventarioDispositivo->delete();
            });

            return redirect()->route('inventario-dispositivos.index')
                ->with('success','Dispositivo eliminado correctamente.');
        } catch (\Exception $e) {
            return back()->with('error','Error al eliminar el dispositivo: '.$e->getMessage());
        }
    }

    private function registrarBajaEnLog($dispositivo, $usuario): void
    {
        $nombreCompleto = trim(($usuario->nombres ?? '').' '.($usuario->apellidos ?? '')) ?: ($usuario->name ?? '—');
        $colaboradorNombre = $dispositivo->colaborador
            ? trim(($dispositivo->colaborador->nombre ?? '').' '.($dispositivo->colaborador->apellidos ?? ''))
            : null;

        DB::table('log_bajas')->insert([
            'tabla_afectada'         => 'inventario_dispositivos',
            'registro_id'            => $dispositivo->id,
            'usuario_ti_id'          => $usuario->id ?? null,
            'usuario_nombre_completo'=> $nombreCompleto,
            'accion'                 => 'DELETE',
            'estado_texto'           => $dispositivo->estado,
            'usuario_nombre'         => $colaboradorNombre,
            'marca_id'               => $dispositivo->marca_id,
            'numero_serie'           => $dispositivo->numero_serie ?? $dispositivo->serie,
            'modelo'                 => $dispositivo->modelo,
            'mac_address'            => $dispositivo->mac,
            'fecha_ultima_edicion'   => $dispositivo->updated_at,
            'created_at'             => now(),
            'updated_at'             => now(),
        ]);
    }

    /* ============================ QR ============================ */

    public function generarQR(InventarioDispositivo $inventarioDispositivo)
    {
        try {
            $inventarioDispositivo->load(['tipo','marca','colaborador']);

            $texto  = $this->generarDatosQR($inventarioDispositivo);
            $size   = (int) request('size', 300);
            $format = request('format', function_exists('imagecreatetruecolor') ? 'png' : 'svg');

            $qrContent  = $this->generarQRCode($texto, $size, $format);
            $contentType= $format === 'svg' ? 'image/svg+xml' : 'image/png';
            $filename   = "qr_dispositivo_{$inventarioDispositivo->id}.{$format}";

            return response($qrContent, 200)
                ->header('Content-Type', $contentType)
                ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
        } catch (\Exception $e) {
            abort(500, 'Error al generar QR: '.$e->getMessage());
        }
    }

    private function generarQRCode(string $data, int $size = 200, string $format = 'png'): string
    {
        $writer = $format === 'svg' ? new SvgWriter() : new PngWriter();

        $result = Builder::create()
            ->writer($writer)
            ->data($data)
            ->size($size)
            ->margin(10)
            ->encoding(new Encoding('UTF-8'))
            ->foregroundColor(new Color(0,0,0))
            ->backgroundColor(new Color(255,255,255))
            ->build();

        return $result->getString();
    }

    private function generarDatosQR($d): string
    {
        if (!$d) return "Dispositivo no encontrado";

        $txt  = "INVENTARIO DE DISPOSITIVO\n";
        $txt .= "=======================\n";
        $txt .= "ID: ".($d->id ?? 'N/A')."\n";
        $txt .= "Tipo: ".($d->tipo->nombre ?? 'No especificado')."\n";
        $txt .= "Marca: ".($d->marca->nombre ?? 'No especificado')."\n";
        $txt .= "Modelo: ".($d->modelo ?? 'N/A')."\n";
        $txt .= "N° Serie: ".($d->numero_serie ?? 'N/A')."\n";
        if (!empty($d->serie) && $d->serie !== 'N/A') $txt .= "Serie: {$d->serie}\n";
        $txt .= "Estado: ".ucfirst($d->estado ?? 'desconocido')."\n";
        if (!empty($d->mac) && $d->mac !== 'N/A') $txt .= "MAC: {$d->mac}\n";
        $txt .= "Asignado a: ".(
            $d->colaborador ? trim(($d->colaborador->nombre ?? '').' '.($d->colaborador->apellidos ?? '')) : 'No asignado'
        )."\n";
        if (!empty($d->procesador) && $d->procesador !== 'N/A') $txt .= "Procesador: {$d->procesador}\n";
        if (!empty($d->memoria_ram) && $d->memoria_ram !== 'N/A') $txt .= "RAM: {$d->memoria_ram}\n";
        $txt .= "Actualizado: ".Carbon::parse($d->updated_at)->format('d/m/Y');

        return $txt;
    }
}
