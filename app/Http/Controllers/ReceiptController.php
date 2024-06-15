<?php

namespace App\Http\Controllers;
use App\Models\Purchase;
use App\Http\Controllers\QrCodeController;
use Illuminate\Support\Facades\Storage;

class ReceiptController extends Controller
{
    public function show(Purchase $purchase)
    {
        if($purchase->receipt_pdf_filename == null || !(Storage::exists('public/pdf_receipts/' . $purchase->receipt_pdf_filename))){
        $this::storePDF($purchase);
        }
        return view('receipt', ['purchase' => $purchase, 'qr_codes' => QrCodeController::generate($purchase)]);
    }
    public static function storePDF(Purchase $purchase){
        PdfController::generatePdf($purchase);
    }
}
