<?php

namespace App\Http\Controllers;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;
use App\Models\Purchase; 
class PdfController extends Controller
{
    public static function generatePdf(Purchase $purchase)
    {
        // Fetch the data and pass it to the view
        $data = [
            'purchase' => $purchase,
            'qr_codes' => QrCodeController::generate($purchase), // Assuming QrCodeController has a generate method
        ];
        $options = new Options();
        $options->set('isPhpEnabled', true); // Enable PHP interpretation in HTML, if needed
        // Create an instance of the Dompdf class
        $dompdf = new Dompdf();

        // Load HTML content from view
        $html = view('receipt', $data)->render();

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF (important for saving)
        $dompdf->render();

        // Generate a unique filename for the PDF
        $fileName = 'document_' . md5(uniqid()) . '.pdf';

        // Store PDF in storage/app/public/pdf_receipts directory
        Storage::put('public/pdf_receipts/' . $fileName, $dompdf->output());

        // Optionally, you can store the file path in your Purchase model
        $purchase->receipt_pdf_filename = $fileName;
        $purchase->save();
    }
}
