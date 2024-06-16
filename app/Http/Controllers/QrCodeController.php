<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Ticket;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    public static function generateReceipt(Purchase $purchase)
    {
        $qr_codes = array();
        foreach($purchase->tickets as $ticket){
            $qr_codes[] = QrCode::format('png')->size(300)->generate($ticket->qrcode_url);
        }
        return $qr_codes;
    }

    public static function generateTicket(Ticket $ticket)
    {
        return QrCode::format('png')->size(300)->generate($ticket->qrcode_url);
    }
}

