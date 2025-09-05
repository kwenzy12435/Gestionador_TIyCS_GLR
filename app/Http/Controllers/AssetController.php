<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class AssetController extends Controller
{
    public function serveCss($filename)
    {
        // Verificar que el archivo tenga extensión .css
        if (!preg_match('/\.css$/', $filename)) {
            abort(404);
        }

        $path = resource_path('css/' . $filename);
        
        if (!File::exists($path)) {
            abort(404, "Archivo CSS no encontrado: " . $filename);
        }

        $file = File::get($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", "text/css");
        $response->header("Cache-Control", "public, max-age=3600");

        return $response;
    }

    public function serveJs($filename)
    {
        // Verificar que el archivo tenga extensión .js
        if (!preg_match('/\.js$/', $filename)) {
            abort(404);
        }

        $path = resource_path('js/' . $filename);
        
        if (!File::exists($path)) {
            abort(404, "Archivo JS no encontrado: " . $filename);
        }

        $file = File::get($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", "application/javascript");
        $response->header("Cache-Control", "public, max-age=3600");

        return $response;
    }
}