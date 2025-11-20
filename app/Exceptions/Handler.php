<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Campos que nunca se guardan en sesión cuando hay validación.
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        //
    }

    public function render($request, Throwable $e)
    {
        // Para APIs/JSON, deja el comportamiento normal
        if ($request->expectsJson()) {
            return parent::render($request, $e);
        }

        $debug = (bool) config('app.debug');

        // Errores HTTP conocidos (404, 403, 405, 419, 422, 429, 500, 503…)
        if ($e instanceof HttpExceptionInterface) {
            $code = $e->getStatusCode();
            $title = match ($code) {
                403 => 'Acceso denegado',
                404 => 'No encontrado',
                405 => 'Método no permitido',
                419 => 'Sesión expirada',
                422 => 'Datos inválidos',
                429 => 'Demasiadas solicitudes',
                500 => 'Error interno',
                503 => 'Servicio no disponible',
                default => 'Ha ocurrido un problema',
            };

            return response()->view('errors.universal', [
                'code'        => $code,
                'title'       => $title,
                'description' => $e->getMessage() ?: $title,
            ], $code);
        }

        // Excepciones no-HTTP: si debug está OFF, muestra 500 personalizado
        if (! $debug) {
            return response()->view('errors.universal', [
                'code'        => 500,
                'title'       => 'Error interno',
                'description' => 'Ha ocurrido un error inesperado.',
            ], 500);
        }

        // En modo debug, muestra el debugger de Laravel
        return parent::render($request, $e);
    }
}
