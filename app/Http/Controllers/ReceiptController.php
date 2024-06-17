<?php

namespace App\Http\Controllers;
use App\Models\Purchase;
use App\Http\Controllers\QrCodeController;
use Illuminate\Support\Facades\Storage;

class ReceiptController extends Controller
{
    public static function storePDFReceipt(Purchase $purchase){
        PdfController::generatePdfReceipt($purchase);
    }

    public function show(Purchase $purchase){
        return view('receipt')->with('purchase', $purchase);
    }
}
