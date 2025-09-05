<?php

namespace App\Helpers;

use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer;

class QrCodeHelper
{
    public static function generate($text, $size = 300)
    {
        $renderer = new Png();
        $renderer->setHeight($size);
        $renderer->setWidth($size);
        
        $writer = new Writer($renderer);
        return $writer->writeString($text);
    }
    
    public static function generateBase64($text, $size = 300)
    {
        $qrCode = self::generate($text, $size);
        return 'data:image/png;base64,' . base64_encode($qrCode);
    }
}
?>