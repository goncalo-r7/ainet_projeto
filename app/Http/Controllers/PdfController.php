<?php

namespace App\Http\Controllers;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;
use App\Models\Purchase;
use App\Models\Ticket;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReceiptMail;

class PdfController extends Controller
{
    public static function generatePdfReceipt(Purchase $purchase)
    {
        $data = [
            'purchase' => $purchase,
            'qr_codes' => QrCodeController::generateReceipt($purchase),
        ];
        $options = new Options();
        $options->set('isPhpEnabled', true);
        $dompdf = new Dompdf();
        $html = view('receipt', $data)->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $purchase->receipt_pdf_filename = PdfController::storePDF($dompdf, 'public/pdf_receipts/');;
        $purchase->save();
        Mail::to($purchase->customer_email)->send(new ReceiptMail($purchase));
    }

    public static function generatePdfTicket(Ticket $ticket)
    {
        $options = new Options();
        $options->set('isPhpEnabled', true);
        $dompdf = new Dompdf();
        $html = view('tickets.show', ['ticket' => $ticket, 'qr_code' => QrCodeController::generateTicket($ticket)])->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        PdfController::loadPDF($dompdf);
    }

    public static function storePDF(Dompdf $pdf, string $path){
                    $fileName = 'document_' . md5(uniqid()) . '.pdf';
                    if(Storage::put($path . $fileName, $pdf->output())){
                        return $fileName;
                    }
                    return null;
    }

    public static function loadPDF(Dompdf $pdf){
        $fileName = 'document_' . md5(uniqid()) . '.pdf';
        $pdf->stream($fileName, ["Attachment" => true]);
    }
}
