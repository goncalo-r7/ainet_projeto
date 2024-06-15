<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Ticket;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    public static function generate(Purchase $purchase)
    {
        // URL que você deseja converter em QR Code
        $qr_codes = array();
        foreach($purchase->tickets as $ticket){
            $qr_codes[] = QrCode::format('png')->size(300)->generate($ticket->qrcode_url);
        }
        // Retorna a view com a imagem do QR Code
        return $qr_codes;
    }
}

