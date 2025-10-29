<?php

namespace App\Helpers;

class ValidationTranslator
{
    public static function translate(string $message): string
    {
        $translations = [
            // Errores generales
            'The provided credentials are invalid.' => 'Las credenciales proporcionadas no son válidas.',
            'These credentials do not match our records.' => 'Estas credenciales no coinciden con nuestros registros.',
            
            // Errores de campos requeridos
            'The usuario field is required.' => 'El campo usuario es obligatorio.',
            'The contrasena field is required.' => 'El campo contraseña es obligatorio.',
            'The password field is required.' => 'El campo contraseña es obligatorio.',
            'The email field is required.' => 'El campo correo electrónico es obligatorio.',
            
            // Errores de formato
            'The usuario must be a string.' => 'El usuario debe ser una cadena de texto.',
            'The contrasena must be a string.' => 'La contraseña debe ser una cadena de texto.',
            
            // Errores específicos que puedas tener
            'validation.required' => 'El campo es obligatorio.',
            'validation.string' => 'El campo debe ser texto.',
            
            // Si recibes mensajes en formato array
            'usuario.required' => 'El campo usuario es obligatorio.',
            'contrasena.required' => 'El campo contraseña es obligatorio.',
        ];

        return $translations[$message] ?? $message;
    }
}